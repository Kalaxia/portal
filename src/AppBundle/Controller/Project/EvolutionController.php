<?php
namespace AppBundle\Controller\Project;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\{
    Method,
    Route,
    Security
};

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Request,
    Response
};

use AppBundle\Utils\Parser;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    NotFoundHttpException
};

use AppBundle\Manager\Project\{
    EvolutionManager,
    LabelManager
};
use AppBundle\Manager\Vote\PollManager;

class EvolutionController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
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
     * @Security("has_role('ROLE_USER')")
     * @Route("/evolutions/{id}", name="update_evolution")
     * @Method({"PUT"})
     */
    public function updateEvolutionAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        $evolutionManager = $this->get(EvolutionManager::class);
        if (($evolution = $evolutionManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }

        if (!empty($data['status'])) {
            if(!$this->isGranted('ROLE_DEVELOPER')) {
                throw new AccessDeniedHttpException('project.feedback.not_developer');
            }
            $evolution->setStatus($data['status']);
        }
        if (!empty($data['description'])) {
            if($evolution->getAuthor()->getId() != $this->getUser()->getId()) {
                throw new AccessDeniedHttpException('project.feedback.not_author');
            }
            $evolution->setDescription($this->get(Parser::class)->parse($data['description']));
        }
        return new JsonResponse($evolutionManager->update($evolution, $this->getUser()));
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/evolutions/{id}/labels/{label_id}", name="add_label_to_evolution")
     * @Method({"POST"})
     */
    public function addLabelToEvolutionAction(Request $request)
    {
        $evolutionManager = $this->get(EvolutionManager::class);
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        if (empty($labelId = $request->attributes->get('label_id'))) {
            throw new BadRequestHttpException('project.feedback.missing_label_id');
        }
        if (($evolution = $evolutionManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        if ($this->getUser()->getId() !== $evolution->getAuthor()->getId() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('project.feedback.access_denied');
        }
        $evolutionManager->addLabelToEvolution($evolution, $labelId);
        return new Response('', 204);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/evolutions/{id}/labels/{label_id}", name="remove_label_from_evolution")
     * @Method({"DELETE"})
     */
    public function removeLabelFromEvolutionAction(Request $request)
    {
        $evolutionManager = $this->get(EvolutionManager::class);
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        if (empty($labelId = $request->attributes->get('label_id'))) {
            throw new BadRequestHttpException('project.feedback.missing_label_id');
        }
        if (($evolution = $evolutionManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        if ($this->getUser()->getId() !== $evolution->getAuthor()->getId() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('project.feedback.access_denied');
        }
        $evolutionManager->removeLabelFromEvolution($evolution, $labelId);
        return new Response('', 204);
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
            'feedback' => $evolution,
            'poll' => $this->get(PollManager::class)->getActivePollByFeature($evolution),
            'labels' => $this->get(LabelManager::class)->getAll()
        ]);
    }
}
