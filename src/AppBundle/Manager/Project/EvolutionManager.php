<?php

namespace AppBundle\Manager\Project;

use AppBundle\Gateway\FeedbackGateway;

use AppBundle\Manager\NotificationManager;

use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;

use AppBundle\Model\Project\Evolution;

use AppBundle\Utils\Parser;

class EvolutionManager
{
    /** @var FeedbackGateway **/
    protected $gateway;
    /** @var CommentaryManager **/
    protected $commentaryManager;
    /** @var NotificationManager **/
    protected $notificationManager;
    /** @var UserManager **/
    protected $userManager;
    /** @var Parser **/
    protected $parser;
    
    /**
     * @param FeedbackGateway $gateway
     * @param CommentaryManager $commentaryManager
     * @param NotificationManager $notificationManager
     * @param UserManager $userManager
     * @param Parser $parser
     */
    public function __construct(
        FeedbackGateway $gateway,
        CommentaryManager $commentaryManager,
        NotificationManager $notificationManager,
        UserManager $userManager,
        Parser $parser
    )
    {
        $this->gateway = $gateway;
        $this->commentaryManager = $commentaryManager;
        $this->notificationManager = $notificationManager;
        $this->userManager = $userManager;
        $this->parser = $parser;
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
        $updatedEvolution = $this->gateway->updateEvolution($evolution);
        
        $title = 'Evolution mise à jour';
        $content = "{$user->getUsername()} a mis à jour l'évolution \"{$evolution->getTitle()}\".";
        // We avoid sending notification to the updater, whether he is the feedback author or not
        $players = [$user->getId()];
        if ($evolution->getAuthor()->getId() !== $user->getId() && $evolution->getAuthor()->getId() !== 0) {
            $players[] = $evolution->getAuthor()->getId();
            $this->notificationManager->create($evolution->getAuthor(), $title, $content);
        }
        foreach ($evolution->getCommentaries() as $comment) {
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
            ->setDescription($data['description'])
            ->setStatus($data['status'])
            ->setAuthor($this->getAuthor($data['author']['username'], $getAuthor))
            ->setCreatedAt(new \DateTime($data['created_at']))
            ->setUpdatedAt(new \DateTime($data['updated_at']))
        ;
        if (!empty($data['commentaries'])) {
            foreach ($data['commentaries'] as $commentary) {
                $evolution->addCommentary($this->commentaryManager->format($commentary, true));
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