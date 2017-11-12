<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Model\Project\Feedback;

class ProjectController extends Controller
{
    /**
     * @Route("/board", name="project_board")
     */
    public function boardAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('project/board.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'statuses' => Feedback::getStatuses(),
        ]);
    }
}
