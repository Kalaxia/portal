<?php

namespace AppBundle\Manager\Project;

use AppBundle\Gateway\FeedbackGateway;

use AppBundle\Manager\NotificationManager;
use FOS\UserBundle\Doctrine\UserManager;

use AppBundle\Entity\User;

use AppBundle\Model\Project\Bug;

use AppBundle\Utils\Parser;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BugManager
{
    /** @var FeedbackGateway **/
    protected $gateway;
    /** @var CommentManager **/
    protected $commentManager;
    /** @var LabelManager **/
    protected $labelManager;
    /** @var NotificationManager **/
    protected $notificationManager;
    /** @var UserManager **/
    protected $userManager;
    /** @var Parser **/
    protected $parser;
    /** @var UrlGeneratorInterface **/
    protected $router;

    /**
     * @param FeedbackGateway $gateway
     * @param CommentManager $commentManager
     * @param LabelManager $labelManager
     * @param NotificationManager $notificationManager
     * @param UserManager $userManager
     * @param Parser $parser
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        FeedbackGateway $gateway,
        CommentManager $commentManager,
        LabelManager $labelManager,
        NotificationManager $notificationManager,
        UserManager $userManager,
        Parser $parser,
        UrlGeneratorInterface $router
    )
    {
        $this->gateway = $gateway;
        $this->commentManager = $commentManager;
        $this->labelManager = $labelManager;
        $this->notificationManager = $notificationManager;
        $this->userManager = $userManager;
        $this->parser = $parser;
        $this->router = $router;
    }

    /**
     * @param string $title
     * @param string $description
     * @param User $user
     * @return mixed
     */
    public function create($title, $description, User $user)
    {
        return $this->format(json_decode($this->gateway->createBug(
            $title,
            $this->parser->parse($description),
            Bug::STATUS_TO_SPECIFY,
            $user->getUsername(),
            $user->getEmail()
        )->getBody(), true));
    }

    /**
     * @param Bug $bug
     * @param User $user
     * @return Response
     */
    public function update(Bug $bug, User $user)
    {
        $updatedBug = $this->format(json_decode($this->gateway->updateBug($bug)->getBody(), true));

        $title = 'Bug mis à jour';
        // We get bug URL from the slug, who is necessarily not empty, because we just updated it.
        $url = $this->router->generate('get_bug', ['id' => $updatedBug->getSlug()]);
        $content = "{$user->getUsername()} a mis à jour <a href=\"$url\">le bug \"{$bug->getTitle()}\"</a>";

        // We avoid sending notification to the updater, whether he is the feedback author or not.
        $players = [$user->getId()];
        if ($bug->getAuthor()->getId() !== $user->getId() && $bug->getAuthor()->getId() !== 0) {
            $players[] = $bug->getAuthor()->getId();
            $this->notificationManager->add($bug->getAuthor(), $title, $content);
        }
        foreach ($bug->getComments() as $comment) {
            $commentAuthor = $comment->getAuthor();

            if (in_array($commentAuthor->getId(), $players) || $commentAuthor->getId() === 0) {
                continue;
            }
            $players[] = $commentAuthor->getId();
            $this->notificationManager->create($commentAuthor, $title, $content);
        }
        return $updatedBug;
    }

    /**
     * @param Bug $bug
     * @param string $labelId
     */
    public function addLabelToBug(Bug $bug, $labelId)
    {
        $this->gateway->addLabelToBug($bug, $labelId);
    }

    /**
     * @param Bug $bug
     * @param string $labelId
     */
    public function removeLabelFromBug(Bug $bug, $labelId)
    {
        $this->gateway->removeLabelFromBug($bug, $labelId);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = json_decode($this->gateway->getBugs()->getBody(), true);
        foreach ($result as &$data) {
            $data = $this->format($data);
        }
        return $result;
    }

    /**
     * @param string $id
     * @return Bug
     */
    public function get($id)
    {
        return $this->format(json_decode($this->gateway->getBug($id)->getBody(), true), true);
    }

    /**
     * @param array $data
     * @param boolean $getAuthor
     * @return Bug
     */
    protected function format($data, $getAuthor = false)
    {
        $bug =
            (new Bug())
            ->setId($data['id'])
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
                $bug->addComment($this->commentManager->format($comment, true));
            }
        }
        if (!empty($data['labels'])) {
            foreach ($data['labels'] as $label) {
                $bug->addLabel($this->labelManager->format($label));
            }
        }
        return $bug;
    }

    protected function getAuthor($name, $getAuthorData = false)
    {
        if ($getAuthorData === false) {
            return $name;
        }
        if (($author = $this->userManager->findUserByUsername($name)) === null) {
            return
                (new User())
                ->setUsername($name)
            ;
        }
        return $author;
    }
}
