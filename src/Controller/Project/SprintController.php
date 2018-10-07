<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;

use Scrumban\Manager\SprintManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SprintController extends Controller
{
    /**
     * @Route("/sprint/{id}", name="sprint_details", methods={"GET"})
     */
    public function getSprint(int $id, SprintManager $sprintManager)
    {
        if (($sprint = $sprintManager->get($id)) === null) {
            throw new NotFoundHttpException('Sprint not found');
        }
        return $this->render('project/sprint_details.html.twig', [
            'sprint' => $sprint
        ]);
    }
}