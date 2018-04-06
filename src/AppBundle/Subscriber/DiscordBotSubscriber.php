<?php

namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Gateway\DiscordBotGateway;

use AppBundle\Event\Poll\CreationEvent;

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
            CreationEvent::NAME => 'onPollCreation',
        ];
    }
    
    /**
     * @param CreationEvent $event
     */
    public function onPollCreation(CreationEvent $event)
    {
        $this->gateway->notifyPollCreation($event->getPoll()->getId());
    }
}