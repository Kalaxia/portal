<?php

namespace AppBundle\Controller\Game;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\{
    Method,
    Route,
    Security
};

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Manager\Game\FactionManager;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FactionController extends Controller
{
    /**
     * @Route("/admin/factions", name="factions_admin_list")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getFactionsAction()
    {
        return $this->render('admin/game/faction/list.html.twig', [
            'factions' => $this->get(FactionManager::class)->getAll()
        ]);
    }
    
    /**
     * @Route("/admin/factions/new", name="game_new_faction")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newFactionAction()
    {
        return $this->render('admin/game/faction/new.html.twig');
    }
    
    /**
     * @Route("/admin/factions", name="game_create_faction")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createFactionAction(Request $request)
    {
        if (empty($name = $request->request->get('name'))) {
            throw new BadRequestHttpException('game.faction.missing_name');
        }
        if (empty($description = $request->request->get('description'))) {
            throw new BadRequestHttpException('game.faction.missing_description');
        }
        if (empty($color = $request->request->get('color'))) {
            throw new BadRequestHttpException('game.faction.missing_color');
        }
        if (empty($banner = $request->request->get('banner'))) {
            throw new BadRequestHttpException('game.faction.missing_banner');
        }
        $this->get(FactionManager::class)->create($name, $description, $color, $banner);
        return $this->redirectToRoute('factions_admin_list');
    }
}