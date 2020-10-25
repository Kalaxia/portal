<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Manager\Project\LabelManager;
use App\Manager\Game\ServerManager;
use App\Manager\Vote\PollManager;
use App\Manager\UserManager;

use Symfony\Component\HttpFoundation\Request;

use App\RSS\Parser;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, Parser $parser)
    {
        $parser->feed("https://kalaxia.org/?feed=rss2");

        return $this->render('front/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tickets' => $parser->items,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/dashboard.html.twig');
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function adminDashboardAction(Request $request, UserManager $userManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('front/admin_dashboard.html.twig', [
            'new_users' => $userManager->getLastUsers(),
        ]);
    }
}
