<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Gateway\DiscordBotGateway;
use Symfony\Contracts\Translation\TranslatorInterface;

class DiscordBotSubscriber implements EventSubscriberInterface
{
    /** @var DiscordBotGateway **/
    protected $gateway;
    /** @var TranslatorInterface **/
    protected $translator;

    public function __construct(DiscordBotGateway $gateway, TranslatorInterface $translator)
    {
        $this->gateway = $gateway;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}