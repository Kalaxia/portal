<?php

namespace AppBundle\Manager\Project;

use AppBundle\Gateway\FeedbackGateway;

use AppBundle\Entity\User;
use FOS\UserBundle\Doctrine\UserManager;

use AppBundle\Manager\NotificationManager;

use AppBundle\Model\Project\{Comment, Feedback};

use AppBundle\Utils\Parser;

class CommentManager
{
    /** @var FeedbackGateway **/
    protected $feedbackGateway;
    /** @var NotificationManager **/
    protected $notificationManager;
    /** @var UserManager **/
    protected $userManager;
    /** @var Parser **/
    protected $parser;
    
    /**
     * @param FeedbackGateway $feedbackGateway
     * @param NotificationManager $notificationManager
     * @param UserManager $userManager
     * @param Parser $parser
     */
    public function __construct(
        FeedbackGateway $feedbackGateway,
        NotificationManager $notificationManager,
        UserManager $userManager,
        Parser $parser
    )
    {
        $this->feedbackGateway = $feedbackGateway;
        $this->notificationManager = $notificationManager;
        $this->userManager = $userManager;
        $this->parser = $parser;
    }
    
    /**
     * @param string $feedbackId
     * @param string $feedbackType
     * @param string $content
     * @param User $author
     * @return Response
     */
    public function create(Feedback $feedback, $content, User $author)
    {
        $comment = $this->format(json_decode($this
            ->feedbackGateway
            ->createComment(
                $feedback->getId(),
                $feedback->getType(),
                $this->parser->parse($content),
                $author->getUsername(),
                $author->getEmail()
            )
            ->getBody()
        , true));
        
        $title = 'Nouveau commentaire';
        $content =
            "{$author->getUsername()} a posté un commentaire sur " .
            (($feedback->getType() === Feedback::TYPE_BUG) ? 'le bug ': 'l\'évolution ') .
            " \"{$feedback->getTitle()}\"."
        ;
        // We avoid sending notification to the comment author, whether he is the feedback author or not
        $players = [$author->getId()];
        if ($feedback->getAuthor()->getId() !== $author->getId() && $feedback->getAuthor()->getId() !== 0) {
            $players[] = $feedback->getAuthor()->getId();
            $this->notificationManager->create($feedback->getAuthor(), $title, $content);
        }
        foreach ($feedback->getComments() as $c) {
            $commentAuthor = $c->getAuthor();
            
            if (in_array($commentAuthor->getId(), $players) || $commentAuthor->getId() === 0) {
                continue;
            }
            $players[] = $commentAuthor->getId();
            $this->notificationManager->create($commentAuthor, $title, $content);
        }
        return $comment;
    }
    
    /**
     * @param type $data
     * @param type $getAuthor
     * @return type
     */
    public function format($data, $getAuthor = false)
    {
        return
            (new Comment())
            ->setId($data['id'])
            ->setContent($data['content'])
            ->setAuthor($this->getAuthor($data['author']['username'], $getAuthor))
            ->setCreatedAt(new \DateTime($data['created_at']))
            ->setUpdatedAt(new \DateTime($data['updated_at']))
        ;
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