<?php

namespace App\Controller\Vote;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\Project\FeedbackManager;
use App\Manager\Vote\{
    OptionManager,
    PollManager,
    VoteManager
};
use App\Model\Project\Feedback;

class PollController extends Controller
{
    /**
     * @Route("/feedbacks/{feedback_id}/vote", name="launch_feedback_poll", methods={"GET"})
     */
    public function createFeaturePollAction(Request $request, FeedbackManager $feedbackManager, PollManager $pollManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (($feedback = $feedbackManager->get($request->attributes->get('feedback_id'))) === null) {
            throw new NotFoundHttpException();
        }
        if ($feedback->getType() !== Feedback::TYPE_EVOLUTION)
        {
            throw new BadRequestHttpException('polls.wrong_feedback_type');
        }
        $poll = $pollManager->createFeaturePoll($feedback);
        return $this->redirectToRoute('get_poll', [
            'id' => $poll->getId()
        ]);
    }
    
    /**
     * @Route("/polls/{id}", name="get_poll")
     */
    public function getPollAction(Request $request, PollManager $pollManager, OptionManager $optionManager, VoteManager $voteManager)
    {
        $poll = $pollManager->get($request->attributes->get('id'));
        return $this->render('vote/details.html.twig', [
            'poll' => $poll,
            'options' => $optionManager->getPollOptions($poll),
            'has_voted' => ($this->getUser()) ? $voteManager->hasAlreadyVoted($poll, $this->getUser()) : false,
            'other_polls' => $pollManager->getActivePolls()
        ]);
    }
}

