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
    FeedbackManager,
    LabelManager
};
use AppBundle\Manager\Vote\PollManager;

class FeedbackController extends Controller
{
    /**
     * @Route("/feedbacks/search", name="search_feedbacks", methods={"POST"})
     */
    public function searchAction(Request $request, FeedbackManager $feedbackManager)
    {
        $data = json_decode($request->getContent(), true);
        
        return new JsonResponse($feedbackManager->search($data['title']));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/feedbacks/new", name="new_feedback")
     * @Method({"GET"})
     */
    public function newFeedbackAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('project/new_feedback.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'type' => $request->query->get('type', 'bug')
        ]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/feedbacks", name="create_feedback")
     * @Method({"POST"})
     */
    public function createFeedbackAction(Request $request, FeedbackManager $feedbackManager)
    {
        if (empty($title = trim($request->request->get('title')))) {
            throw new BadRequestHttpException('project.feedback.missing_title');
        }
        if (empty($type = trim($request->request->get('type')))) {
            throw new BadRequestHttpException('project.feedback.missing_type');
        }
        if (empty($description = trim($request->request->get('description')))) {
            throw new BadRequestHttpException('project.feedback.missing_description');
        }
        return $this->redirectToRoute('get_feedback', [
            'id' => $feedbackManager
                ->create($title, $type, $description, $this->getUser())
                ->getId()
        ]);
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/feedbacks/{id}", name="update_feedback")
     * @Method({"PUT"})
     */
    public function updateFeedbackAction(Request $request, FeedbackManager $feedbackManager, Parser $parser)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        if (($feedback = $feedbackManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        $oldStatus = $feedback->getStatus();
        if (!empty($data['status'])) {
            if(!$this->isGranted('ROLE_DEVELOPER')) {
                throw new AccessDeniedHttpException('project.feedback.not_developer');
            }
            $feedback->setStatus($data['status']);
        }
        if (!empty($data['description'])) {
            if($feedback->getAuthor()->getId() != $this->getUser()->getId()) {
                throw new AccessDeniedHttpException('project.feedback.not_author');
            }
            $feedback->setDescription($parser->parse($data['description']));
        }
        return new JsonResponse($feedbackManager->update($feedback, $oldStatus, $this->getUser()));
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/feedbacks/{id}/labels/{label_id}", name="add_label_to_feedback")
     * @Method({"POST"})
     */
    public function addLabelToFeedbackAction(Request $request, FeedbackManager $feedbackManager)
    {
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        if (empty($labelId = $request->attributes->get('label_id'))) {
            throw new BadRequestHttpException('project.feedback.missing_label_id');
        }
        if (($feedback = $feedbackManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        if ($this->getUser()->getId() !== $feedback->getAuthor()->getId() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('project.feedback.access_denied');
        }
        $feedbackManager->addLabelToFeedback($feedback, $labelId);
        return new Response('', 204);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/feedbacks/{id}/labels/{label_id}", name="remove_label_from_feedback")
     * @Method({"DELETE"})
     */
    public function removeLabelFromFeedbackAction(Request $request, FeedbackManager $feedbackManager)
    {
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.feedback.missing_id');
        }
        if (empty($labelId = $request->attributes->get('label_id'))) {
            throw new BadRequestHttpException('project.feedback.missing_label_id');
        }
        if (($feedback = $feedbackManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        if ($this->getUser()->getId() !== $feedback->getAuthor()->getId() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('project.feedback.access_denied');
        }
        $feedbackManager->removeLabelFromFeedback($feedback, $labelId);
        return new Response('', 204);
    }

    /**
     * @Route("/feedbacks/{id}", name="get_feedback", methods={"GET"})
     */
    public function getAction($id, FeedbackManager $feedbackManager, PollManager $pollManager, LabelManager $labelManager)
    {
        if (($feedback = $feedbackManager->get($id)) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        return $this->render('project/feedback.html.twig', [
            'feedback' => $feedback,
            'poll' => $pollManager->getLastFeaturePoll($feedback),
            'labels' => $labelManager->getAll()
        ]);
    }
    
    /**
     * @Route("/feedbacks/{id}", name="remove_feedback", methods={"DELETE"})
     */
    public function removeAction($id, FeedbackManager $feedbackManager)
    {
        $feedbackManager->remove($id);
        return new Response('', 204);
    }
}
