<?php

namespace App\Controller\Game;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\Game\FactionManager;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FactionController extends Controller
{
    /**
     * @Route("/admin/factions", name="factions_admin_list", methods={"GET"})
     */
    public function getFactionsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/game/faction/list.html.twig', [
            'factions' => $this->get(FactionManager::class)->getAll()
        ]);
    }

    /**
     * @Route("/admin/factions/new", name="game_new_faction")
     */
    public function newFactionAction()
    {
        return $this->render('admin/game/faction/new.html.twig');
    }

    /**
     * @Route("/admin/factions", name="game_create_faction", methods={"POST"})
     */
    public function createFactionAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (empty($name = $request->request->get('name'))) {
            throw new BadRequestHttpException('game.faction.missing_name');
        }
        if (empty($description = $request->request->get('description'))) {
            throw new BadRequestHttpException('game.faction.missing_description');
        }

        if (empty($banner = $request->request->get('banner'))) {
            throw new BadRequestHttpException('game.faction.missing_banner');
        }

        $colorSet = $this->get(FactionManager::class)->createColorSet($request->request->all());
        $this->get(FactionManager::class)->create($name, $description, $colorSet, $banner);
        return $this->redirectToRoute('factions_admin_list');
    }
}
