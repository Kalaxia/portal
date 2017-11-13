<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/bugs", name="create_bug")
     * @Method({"POST"})
     */
    public function createBugAction(Request $request)
    {
        if (empty($title = $request->request->get('title'))) {
            throw new BadRequestHttpException('project.feedback.missing_title');
        }
        if (empty($description = $request->request->get('description'))) {
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
     * @Route("/bugs/{id}", name="get_bug")
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
