<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\{
    Method,
    Route,
    Security
};

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Manager\NotificationManager;

class NotificationController extends Controller
{
    /**
     * @Route("/notifications/{id}/read", name="read_notification")
     * @Method({"PUT"})
     * @Security("has_role('ROLE_USER')")
     */
    public function readAction(Request $request)
    {
        $this->get(NotificationManager::class)->read($request->attributes->get('id'), $this->getUser());
        
        return new Response('', 204);
    }
}