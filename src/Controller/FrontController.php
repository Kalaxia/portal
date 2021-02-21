<?php

namespace App\Controller;

use App\Manager\Game\ItchManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\RSS\Parser;
use Symfony\Contracts\Cache\ItemInterface;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Parser $parser)
    {
        $cache = new FilesystemAdapter();
        $rssFeedItems = $cache->get('blog_rss_feed', function(ItemInterface $item) use ($parser) {
            $item->expiresAfter(3600);

            $parser->feed("https://kalaxia.org/?feed=rss2");

            return $parser->items;
        });
        return $this->render('front/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tickets' => $rssFeedItems,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request, ItchManager $itchManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $gameData = $this->getParameter('itch_games')['client'];

        return $this->render('front/dashboard.html.twig', [
            'game' => $itchManager->getGame($gameData['id']),
            'download_key' => $gameData['download_key'],
        ]);
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
