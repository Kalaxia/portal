<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Manager\NotificationManager;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notifications/{id}/read", name="read_notification", methods={"PUT"})
     */
    public function readAction(Request $request, NotificationManager $notificationManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $notificationManager->read($request->attributes->get('id'), $this->getUser());
        
        return new Response('', 204);
    }
}