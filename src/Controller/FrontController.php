<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use App\Manager\Project\LabelManager;
use App\Manager\Game\ServerManager;
use App\Manager\Vote\PollManager;
use App\Manager\UserManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scrumban\Manager\SprintManager;
use Scrumban\Manager\UserStoryManager;

use App\RSS\Parser;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, Parser $parser, SprintManager $sprintManager, UserStoryManager $userStoryManager)
    {
        $parser->feed("https://kalaxia.org/?feed=rss2");

        $currentSprint = $sprintManager->getCurrentSprint();

        return $this->render('front/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tickets' => $parser->items,
            'current_sprint' => $currentSprint,
            'previous_sprint' => $sprintManager->getPreviousSprint(),
            'user_stories' => ($currentSprint !== null) ? $userStoryManager->getSprintUserStories($currentSprint, ['value' => 'DESC'], 0, 4) : null
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request, ServerManager $serverManager, PollManager $pollManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/dashboard.html.twig', [
            'players_count' => $serverManager->countServersPlayers(),
            'available_servers' => $serverManager->getAvailableServers($this->getUser()),
            'next_servers' => $serverManager->getNextServers(),
            'current_polls' => $pollManager->getActivePolls()
        ]);
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function adminDashboardAction(Request $request, UserManager $userManager, ServerManager $serverManager, LabelManager $labelManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('front/admin_dashboard.html.twig', [
            'new_users' => $userManager->getLastUsers(),
            'opened_servers' => $serverManager->getOpenedServers(),
            'next_servers' => $serverManager->getNextServers(),
            'feedback_labels' => $labelManager->getAll()
        ]);
    }
}
