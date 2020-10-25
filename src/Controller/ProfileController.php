<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        return $this->render('front/profile.html.twig');
    }
}