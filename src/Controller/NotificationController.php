<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Manager\NotificationManager;

class NotificationController extends Controller
{
    /**
     * @Route("/notifications/{id}/read", name="read_notification", methods={"PUT"})
     */
    public function readAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $this->get(NotificationManager::class)->read($request->attributes->get('id'), $this->getUser());
        
        return new Response('', 204);
    }
}