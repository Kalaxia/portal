<?php

namespace AppBundle\Controller\Vote;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use Sensio\Bundle\FrameworkExtraBundle\Configuration\{
    Method,
    Route,
    Security
};

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Manager\Project\EvolutionManager;
use AppBundle\Manager\Vote\{
    OptionManager,
    PollManager,
    VoteManager
};
use AppBundle\Model\Project\Feedback;

class PollController extends Controller
{
    /**
     * @Route("/feedbacks/{feedback_id}/vote", name="launch_feedback_poll")
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function createFeaturePollAction(Request $request)
    {
        if (($feedback = $this->get(EvolutionManager::class)->get($request->attributes->get('feedback_id'))) === null) {
            throw new NotFoundHttpException();
        }
        if ($feedback->getType() !== Feedback::TYPE_EVOLUTION)
        {
            throw new BadRequestHttpException('polls.wrong_feedback_type');
        }
        $poll = $this->get(PollManager::class)->createFeaturePoll($feedback);
        return $this->redirectToRoute('get_poll', [
            'id' => $poll->getId()
        ]);
    }
    
    /**
     * @Route("/polls/{id}", name="get_poll")
     */
    public function getPollAction(Request $request)
    {
        $pollManager = $this->get(PollManager::class);
        $poll = $pollManager->get($request->attributes->get('id'));
        return $this->render('vote/details.html.twig', [
            'poll' => $poll,
            'options' => $this->get(OptionManager::class)->getPollOptions($poll),
            'has_voted' => ($this->getUser()) ? $this->get(VoteManager::class)->hasAlreadyVoted($poll, $this->getUser()) : false,
            'other_polls' => $pollManager->getActivePolls()
        ]);
    }
}

