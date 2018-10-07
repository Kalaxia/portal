<?php

namespace App\Manager\Project;

use App\Model\Project\Feedback;

use App\Event\Feedback\{
    CreationEvent,
    UpdateEvent,
    ValidateEvent,
    DeleteEvent
};

use App\Manager\Project\CommentManager;
use App\Manager\Project\LabelManager;
use App\Gateway\FeedbackGateway;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Manager\NotificationManager;
use App\Utils\Parser;
use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FeedbackManager
{
    /** @var CommentManager **/
    protected $commentManager;
    /** @var LabelManager **/
    protected $labelManager;
    /** @var FeedbackGateway **/
    protected $gateway;
    /** @var UserManagerInterface **/
    protected $userManager;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    /** @var NotificationManager **/
    protected $notificationManager;
    /** @var Parser **/
    protected $parser;
    /** @var UrlGeneratorInterface **/
    protected $router;
    
    /**
     * @param CommentManager $commentManager
     * @param LabelManager $labelManager
     * @param FeedbackGateway $gateway
     * @param UserManagerInterface $userManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param NotificationManager $notificationManager
     * @param Parser $parser
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        CommentManager $commentManager,
        LabelManager $labelManager,
        FeedbackGateway $gateway,
        UserManagerInterface $userManager,
        EventDispatcherInterface $eventDispatcher,
        NotificationManager $notificationManager,
        Parser $parser,
        UrlGeneratorInterface $router
    )
    {
        $this->commentManager = $commentManager;
        $this->labelManager = $labelManager;
        $this->gateway = $gateway;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->notificationManager = $notificationManager;
        $this->parser = $parser;
        $this->router = $router;
    }

    /**
     * @param string $title
     * @param string $type
     * @param string $description
     * @param User $user
     * @return Feedback
     */
    public function create($title, $type, $description, User $user)
    {
        $feedback = $this->format(json_decode($this->gateway->createFeedback(
            $title,
            $type,
            $this->parser->parse($description),
            Feedback::STATUS_TO_SPECIFY,
            $user->getUsername(),
            $user->getEmail()
        )->getBody(), true));
        $this->eventDispatcher->dispatch(CreationEvent::NAME, new CreationEvent($feedback));
        return $feedback;
    }

    /**
     * @param Feedback $feedback
     * @param string $oldStatus
     * @param User $user
     * @return Feedback
     */
    public function update(Feedback $feedback, $oldStatus, User $user)
    {
        $updatedFeedback = $this->format(json_decode($this->gateway->updateFeedback($feedback)->getBody(), true));

        $title = 'Feedback mis à jour';
        // We get evolution URL from the slug, who is necessarily not empty, because we just updated it.
        $url = $this->router->generate('get_feedback', ['id' => $updatedFeedback->getSlug()]);
        $type = ($feedback->getType() === Feedback::TYPE_EVOLUTION) ? 'l\'évolution' : 'le bug';
        $content = "{$user->getUsername()} a mis à jour <a href=\"$url\">$type \"{$feedback->getTitle()}\"</a>.";
        // We avoid sending notification to the updater, whether he is the feedback author or not
        $players = [$user->getId()];
        if ($feedback->getAuthor()->getId() !== $user->getId() && $feedback->getAuthor()->getId() !== 0) {
            $players[] = $feedback->getAuthor()->getId();
            $this->notificationManager->create($feedback->getAuthor(), $title, $content);
        }
        foreach ($feedback->getComments() as $comment) {
            $commentAuthor = $comment->getAuthor();

            if (in_array($commentAuthor->getId(), $players) || $commentAuthor->getId() === 0) {
                continue;
            }
            $players[] = $commentAuthor->getId();
            $this->notificationManager->create($commentAuthor, $title, $content);
        }
        $this->eventDispatcher->dispatch(UpdateEvent::NAME, new UpdateEvent($updatedFeedback, $oldStatus));
        return $updatedFeedback;
    }
    
    public function validate(Feedback $feedback, User $user)
    {
        $feedback->setStatus(Feedback::STATUS_TO_DEPLOY);
        
        $this->gateway->updateFeedback($feedback);
        
        $this->eventDispatcher->dispatch(ValidateEvent::NAME, new ValidateEvent($feedback, $user));
    }

    /**
     * @param Feedback $feedback
     * @param string $labelId
     */
    public function addLabelToFeedback(Feedback $feedback, $labelId)
    {
        $this->gateway->addLabelToFeedback($feedback, $labelId);
    }

    /**
     * @param Feedback $feedback
     * @param string $labelId
     */
    public function removeLabelFromFeedback(Feedback $feedback, $labelId)
    {
        $this->gateway->removeLabelFromFeedback($feedback, $labelId);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = json_decode($this->gateway->getFeedbacks()->getBody(), true);
        foreach ($result as &$data) {
            $data = $this->format($data);
        }
        return $result;
    }

    /**
     * @param string $id
     * @return Feedback
     */
    public function get($id)
    {
        return $this->format(json_decode($this->gateway->getFeedback($id)->getBody(), true), true);
    }
    
    /**
     * @param string $title
     * @return array
     */
    public function search($title)
    {
        $result = json_decode($this->gateway->searchFeedbacks($title)->getBody(), true);
        foreach ($result as &$data) {
            $data = $this->format($data);
        }
        return $result;
    }
    
    /**
     * @param string $id
     * @return Response
     */
    public function remove($id)
    {
        $feedback = $this->get($id);
        $this->eventDispatcher->dispatch(DeleteEvent::NAME, new DeleteEvent($feedback));
        return $this->gateway->deleteFeedback($id);
    }

    /**
     * @param array $data
     * @param boolean $getAuthor
     * @return Feedback
     */
    protected function format($data, $getAuthor = false)
    {
        $feedback =
            (new Feedback())
            ->setId($data['id'])
            ->setType($data['type'])
            ->setTitle($data['title'])
            ->setSlug($data['slug'])
            ->setDescription($data['description'])
            ->setStatus($data['status'])
            ->setAuthor($this->getAuthor($data['author']['username'], $getAuthor))
            ->setCreatedAt(new \DateTime($data['created_at']))
            ->setUpdatedAt(new \DateTime($data['updated_at']))
        ;
        if (!empty($data['comments'])) {
            foreach ($data['comments'] as $comment) {
                $feedback->addComment($this->commentManager->format($comment, true));
            }
        }
        if (!empty($data['labels'])) {
            foreach ($data['labels'] as $label) {
                $feedback->addLabel($this->labelManager->format($label));
            }
        }
        return $feedback;
    }

    /**
     * @param string $username
     * @param boolean $getAuthorData
     */
    protected function getAuthor($username, $getAuthorData = false)
    {
        if ($getAuthorData === false) {
            return $username;
        }
        if (($author = $this->userManager->findUserByUsername($username)) === null) {
            return
                (new User())
                ->setUsername($username)
            ;
        }
        return $author;
    }
    
    /**
     * @return array
     */
    public function getBoardFeedbacks()
    {
        $feedbacks = $this->getAll();
        
        $results = [];
        foreach ($feedbacks as $feedback) {
            $results[$feedback->getStatus()][-$feedback->getUpdatedAt()->getTimestamp()] = $feedback;
        }
        foreach (Feedback::getStatuses() as $status) {
            if (!isset($results[$status])) {
                continue;
            }
            ksort($results[$status]);
        }
        return $results;
    }
}