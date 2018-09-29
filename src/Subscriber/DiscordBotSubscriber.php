<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Gateway\DiscordBotGateway;
use Symfony\Component\Translation\TranslatorInterface;

use App\Event\Poll\CreationEvent as PollCreationEvent;
use App\Event\Feedback\{
    CommentCreationEvent,
    CreationEvent as FeedbackCreationEvent,
    UpdateEvent as FeedbackUpdateEvent,
    ValidateEvent as FeedbackValidateEvent,
    DeleteEvent as FeedbackDeleteEvent
};

class DiscordBotSubscriber implements EventSubscriberInterface
{
    /** @var DiscordBotGateway **/
    protected $gateway;
    /** @var TranslatorInterface **/
    protected $translator;
    
    /**
     * @param DiscordBotGateway $gateway
     * @param TransatorInterface $translator
     */
    public function __construct(DiscordBotGateway $gateway, TranslatorInterface $translator)
    {
        $this->gateway = $gateway;
        $this->translator = $translator;
    }
    
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PollCreationEvent::NAME => 'onPollCreation',
            CommentCreationEvent::NAME => 'onFeedbackCommentCreation',
            FeedbackCreationEvent::NAME => 'onFeedbackCreation',
            FeedbackUpdateEvent::NAME => 'onFeedbackUpdate',
            FeedbackValidateEvent::NAME => 'onFeedbackValidate',
            FeedbackDeleteEvent::NAME => 'onFeedbackDelete',
        ];
    }
    
    /**
     * @param PollCreationEvent $event
     */
    public function onPollCreation(PollCreationEvent $event)
    {
        $this->gateway->notifyPollCreation($event->getPoll()->getId());
    }
    
    /**
     * @param CommentCreationEvent $event
     */
    public function onFeedbackCommentCreation(CommentCreationEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->gateway->notifyFeedbackCommentCreation(
            $feedback->getTitle(),
            $feedback->getSlug(),
            $event->getComment()->getAuthor()
        );
    }
    
    
    /**
     * @param FeedbackCreationEvent $event
     */
    public function onFeedbackCreation(FeedbackCreationEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->gateway->notifyFeedbackCreation(
            $feedback->getTitle(),
            $feedback->getSlug(),
            $this->translator->trans("project.status.{$feedback->getStatus()}")
        );
    }
    
    /**
     * @param FeedbackUpdateEvent $event
     */
    public function onFeedbackUpdate(FeedbackUpdateEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->gateway->notifyFeedbackUpdate(
            $feedback->getTitle(),
            $feedback->getSlug(),
            $this->translator->trans("project.status.{$event->getOldStatus()}"),
            $this->translator->trans("project.status.{$feedback->getStatus()}")
        );
    }
    
    public function onFeedbackValidate(FeedbackValidateEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->gateway->notifyFeedbackValidate(
            $feedback->getTitle(),
            $feedback->getSlug(),
            $event->getUser()->getUsername()
        );
    }
    
    /**
     * @param FeedbackDeleteEvent $event
     */
    public function onFeedbackDelete(FeedbackDeleteEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->gateway->notifyFeedbackDelete(
            $feedback->getTitle(),
            $feedback->getSlug(),
            $this->translator->trans("project.status.{$feedback->getStatus()}")
        );
    }
}