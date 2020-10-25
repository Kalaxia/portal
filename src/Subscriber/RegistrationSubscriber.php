<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;

use FOS\UserBundle\Event\FormEvent;

use Psr\Log\LoggerInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface **/
    protected $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('dashboard')));
    }
}