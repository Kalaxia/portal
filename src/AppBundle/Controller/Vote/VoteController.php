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

use AppBundle\Manager\Vote\{
    VoteManager,
    PollManager
};

class VoteController extends Controller
{
    /**
     * @Route("/polls/{id}/vote/{option_id}", name="vote_poll")
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function votePollAction(Request $request)
    {
        if (($poll = $this->get(PollManager::class)->get($request->attributes->get('id'))) === null) {
            throw new NotFoundHttpException();
        }
        if ($poll->getIsOver()) {
            throw new BadRequestHttpException('polls.already_voted');
        }
        $vote = $this->get(VoteManager::class)->vote($poll, $this->getUser(), $request->attributes->get('option_id'));
        return $this->redirectToRoute('get_poll', [
            'id' => $poll->getId()
        ]);
    }
}

