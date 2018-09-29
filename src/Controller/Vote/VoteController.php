<?php

namespace App\Controller\Vote;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\Vote\{
    VoteManager,
    PollManager
};

class VoteController extends Controller
{
    /**
     * @Route("/polls/{id}/vote/{option_id}", name="vote_poll", methods={"GET"})
     */
    public function votePollAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
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

