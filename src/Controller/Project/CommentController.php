<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};
use App\Manager\Project\{
    CommentManager,
    FeedbackManager
};

class CommentController extends Controller
{
    /**
     * @Route("/feedbacks/{id}/comments", name="create comment", methods={"POST"})
     */
    public function createComment(Request $request, FeedbackManager $feedbackManager, CommentManager $commentManager, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $data = json_decode($request->getContent(), true);
        if(($feedback = $feedbackManager->get($request->attributes->get('id'))) === null) {
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