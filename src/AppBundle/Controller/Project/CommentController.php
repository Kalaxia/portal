<?php

namespace AppBundle\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use Sensio\Bundle\FrameworkExtraBundle\Configuration\{
    Method,
    Route,
    Security
};
use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};
use AppBundle\Manager\Project\{
    CommentManager,
    FeedbackManager
};

class CommentController extends Controller
{
    /**
     * @Route("/feedbacks/{id}/comments", name="create comment")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function createComment(Request $request, FeedbackManager $feedbackManager, CommentManager $commentManager, TranslatorInterface $translator)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['type'])) {
            throw new BadRequestHttpException('project.feedback.missing_type');
        }
        if(($feedback = $feedbackManager->getFeedback($request->attributes->get('id'), $data['type'])) === null) {
            throw new NotFoundHttpException('project.feedback.not_found');
        }
        if (empty($content = trim($data['content']))) {
            throw new BadRequestHttpException('project.comment.missing_content');
        }
        $comment = $commentManager->create($feedback, $content, $this->getUser());
        return new JsonResponse([
            'feedback' => $comment,
            'created_at_string' => $translator->trans('project.comment.created_at', ['%date%' => $comment->getCreatedAt()->format($translator->trans('full_date'))]), 
        ]);
    }
}