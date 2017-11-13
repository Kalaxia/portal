<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use AppBundle\Manager\Project\EvolutionManager;

class EvolutionController extends Controller
{
    /**
     * @Route("/evolutions/new", name="propose_evolution")
     * @Method({"GET"})
     */
    public function newEvolutionAction()
    {
        // replace this example code with whatever you need
        return $this->render('project/new_evolution.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }
    
    /**
     * @Route("/evolutions", name="create_evolution")
     * @Method({"POST"})
     */
    public function createEvolutionAction(Request $request)
    {
        if (empty($title = $request->request->get('title'))) {
            throw new BadRequestHttpException('project.feedback.missing_title');
        }
        if (empty($description = $request->request->get('description'))) {
            throw new BadRequestHttpException('project.feedback.missing_description');
        }
        return $this->redirectToRoute('get_evolution', [
            'id' => $this
                ->get(EvolutionManager::class)
                ->create($title, $description, $this->getUser())
                ->getId()
        ]);
    }
    
    /**
     * @Route("/evolutions/{id}", name="get_evolution")
     */
    public function getAction($id)
    {
        if (($evolution = $this->get(EvolutionManager::class)->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        return $this->render('project/feedback.html.twig', [
            'feedback' => $evolution
        ]);
    }
}
