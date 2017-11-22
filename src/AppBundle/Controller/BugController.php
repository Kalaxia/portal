<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Utils\Parser;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use AppBundle\Manager\Project\BugManager;

class BugController extends Controller
{
    /**
     * @Route("/bugs/new", name="report_bug")
     * @Method({"GET"})
     */
    public function newBugAction()
    {
        // replace this example code with whatever you need
        return $this->render('project/new_bug.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/bugs", name="create_bug")
     * @Method({"POST"})
     */
    public function createBugAction(Request $request)
    {
        if (empty($title = trim($request->request->get('title')))) {
            throw new BadRequestHttpException('project.feedback.missing_title');
        }
        if (empty($description = trim($request->request->get('description')))) {
            throw new BadRequestHttpException('project.feedback.missing_description');
        }
        return $this->redirectToRoute('get_bug', [
            'id' => $this
                ->get(BugManager::class)
                ->create($title, $description, $this->getUser())
                ->getId()
        ]);
    }
    
    /**
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Route("/bugs/{id}", name="update_bug")
     * @Method({"PUT"})
     */
    public function updateBugAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        $bugManager = $this->get(BugManager::class);
        if (($bug = $bugManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }

        if (!empty($data['status'])) {
            if(!$this->getUser()->hasRole('ROLE_DEVELOPER')) {
                throw new AccessDeniedHttpException('project.feedback.not_developer');
            }
            $bug->setStatus($data['status']);
        }
        if (!empty($description = trim($data['description']))) {
            if(!$evolution->getAuthor()->getId() != $this->getUser()->getId()) {
                throw new AccessDeniedHttpException('project.feedback.not_author');
            }
            $bug->setDescription($this->get(Parser::class)->parse($description));
        }
        return new JsonResponse($bugManager->update($bug, $this->getUser()));
    }
    
    /**
     * @Route("/bugs/{id}", name="get_bug")
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        if (($bug = $this->get(BugManager::class)->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        return $this->render('project/feedback.html.twig', [
            'feedback' => $bug
        ]);
    }
}
