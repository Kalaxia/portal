<?php

namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Gateway\DiscordBotGateway;

use AppBundle\Event\Poll\CreationEvent as PollCreationEvent;
use AppBundle\Event\Feedback\{
    CreationEvent as FeedbackCreationEvent,
    UpdateEvent as FeedbackUpdateEvent,
    DeleteEvent as FeedbackDeleteEvent
};

class DiscordBotSubscriber implements EventSubscriberInterface
{
    /** @var DiscordBotGateway **/
    protected $gateway;
    
    /**
     * @param DiscordBotGateway $gateway
     */
    public function __construct(DiscordBotGateway $gateway)
    {
        $this->gateway = $gateway;
    }
    
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PollCreationEvent::NAME => 'onPollCreation',
            FeedbackCreationEvent::NAME => 'onFeedbackCreation',
            FeedbackUpdateEvent::NAME => 'onFeedbackUpdate',
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
     * @param FeedbackCreationEvent $event
     */
    public function onFeedbackCreation(FeedbackCreationEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->gateway->notifyFeedbackCreation(
            $feedback->getTitle(),
            $feedback->getSlug(),
            $feedback->getStatus()
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
            $event->getOldStatus(),
            $feedback->getStatus()
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
            $feedback->getStatus()
        );
    }
}