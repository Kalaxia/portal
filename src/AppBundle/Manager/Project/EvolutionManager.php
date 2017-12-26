<?php

namespace AppBundle\Manager\Project;

use AppBundle\Gateway\FeedbackGateway;

use AppBundle\Manager\NotificationManager;

use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;

use AppBundle\Model\Project\Evolution;

use AppBundle\Utils\Parser;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EvolutionManager
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
     * @return Response
     */
    public function create($title, $description, User $user)
    {
        return $this->format(json_decode($this->gateway->createEvolution(
            $title,
            $this->parser->parse($description),
            Evolution::STATUS_TO_SPECIFY,
            $user->getUsername(),
            $user->getEmail()
        )->getBody(), true));
    }

    /**
     * @param Evolution $evolution
     * @param User $user
     * @return Response
     */
    public function update(Evolution $evolution, User $user)
    {
        $updatedEvolution = $this->format(json_decode($this->gateway->updateEvolution($evolution)->getBody(), true));

        $title = 'Evolution mise à jour';
        // We get evolution URL from the slug, who is necessarily not empty, because we just updated it.
        $url = $this->router->generate('get_evolution', ['id' => $updatedEvolution->getSlug()]);
        $content = "{$user->getUsername()} a mis à jour <a href=\"$url\"\">l'évolution \"{$evolution->getTitle()}\"</a>.";
        // We avoid sending notification to the updater, whether he is the feedback author or not
        $players = [$user->getId()];
        if ($evolution->getAuthor()->getId() !== $user->getId() && $evolution->getAuthor()->getId() !== 0) {
            $players[] = $evolution->getAuthor()->getId();
            $this->notificationManager->create($evolution->getAuthor(), $title, $content);
        }
        foreach ($evolution->getComments() as $comment) {
            $commentAuthor = $comment->getAuthor();

            if (in_array($commentAuthor->getId(), $players) || $commentAuthor->getId() === 0) {
                continue;
            }
            $players[] = $commentAuthor->getId();
            $this->notificationManager->create($commentAuthor, $title, $content);
        }
        return $updatedEvolution;
    }

    /**
     * @param Evolution $evolution
     * @param string $labelId
     */
    public function addLabelToEvolution(Evolution $evolution, $labelId)
    {
        $this->gateway->addLabelToEvolution($evolution, $labelId);
    }

    /**
     * @param Evolution $evolution
     * @param string $labelId
     */
    public function removeLabelFromEvolution(Evolution $evolution, $labelId)
    {
        $this->gateway->removeLabelFromEvolution($evolution, $labelId);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = json_decode($this->gateway->getEvolutions()->getBody(), true);
        foreach ($result as &$data) {
            $data = $this->format($data);
        }
        return $result;
    }

    /**
     * @param string $id
     * @return Evolution
     */
    public function get($id)
    {
        return $this->format(json_decode($this->gateway->getEvolution($id)->getBody(), true), true);
    }

    /**
     * @param array $data
     * @param boolean $getAuthor
     * @return Evolution
     */
    protected function format($data, $getAuthor = false)
    {
        $evolution =
            (new Evolution())
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
                $evolution->addComment($this->commentManager->format($comment, true));
            }
        }
        if (!empty($data['labels'])) {
            foreach ($data['labels'] as $label) {
                $evolution->addLabel($this->labelManager->format($label));
            }
        }
        return $evolution;
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
}
