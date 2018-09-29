<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\Project\FeedbackManager;
use App\Model\Project\Feedback;

class ProjectController extends Controller
{
    /**
     * @Route("/board", name="project_board")
     */
    public function boardAction(FeedbackManager $feedbackManager)
    {
        // replace this example code with whatever you need
        return $this->render('project/board.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'statuses' => Feedback::getStatuses(),
            'feedbacks' => $feedbackManager->getBoardFeedbacks()
        ]);
    }
    
    /**
     * @Route("/project/description", name="project_description")
     */
    public function descriptionAction()
    {
        return $this->render('project/description.html.twig');
    }
}
