<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Security("has_role('ROLE_USER')")
     * @Route("/evolutions", name="create_evolution")
     * @Method({"POST"})
     */
    public function createEvolutionAction(Request $request)
    {
        if (empty($title = trim($request->request->get('title')))) {
            throw new BadRequestHttpException('project.feedback.missing_title');
        }
        if (empty($description = trim($request->request->get('description')))) {
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
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Route("/evolutions/{id}", name="update_evolution")
     * @Method({"PUT"})
     */
    public function updateEvolutionStatusAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        if (empty($data['status'])) {
            throw new BadRequestHttpException('project.feedback.missing_status');
        }
        $evolutionManager = $this->get(EvolutionManager::class);
        if (($evolution = $evolutionManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        $evolution->setStatus($data['status']);
        return new JsonResponse($evolutionManager->update($evolution, $this->getUser()));
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
