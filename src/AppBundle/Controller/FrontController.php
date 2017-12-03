<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Manager\Game\ServerManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('front/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $serverManager = $this->get(ServerManager::class);
        return $this->render('front/dashboard.html.twig', [
            'available_servers' => $serverManager->getAvailableServers($this->getUser()),
            'next_servers' => $serverManager->getNextServers()
        ]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function adminDashboardAction(Request $request)
    {
        $serverManager = $this->get(ServerManager::class);
        return $this->render('front/admin_dashboard.html.twig', [
            'opened_servers' => $serverManager->getOpenedServers(),
            'next_servers' => $serverManager->getNextServers()
        ]);
    }
}
