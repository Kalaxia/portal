<?php

namespace AppBundle\Controller\Game;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use AppBundle\Manager\Game\{
    FactionManager,
    MachineManager,
    ServerManager
};
use AppBundle\Entity\Game\Server;

class ServerController extends Controller
{
    /**
     * @Route("/admin/servers/new", name="new_game_server")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newServerAction()
    {
        return $this->render('admin/game/server/new.html.twig', [
            'factions' => $this->get(FactionManager::class)->getAll(),
            'machines' => $this->get(MachineManager::class)->getAll(),
        ]);
    }
    
    /**
     * @Route("/admin/servers", name="create_game_server", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createServerAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (empty($data['name'])) {
            throw new BadRequestHttpException('game.server.missing_name');
        }
        if (empty($data['description'])) {
            throw new BadRequestHttpException('game.server.missing_description');
        }
        if (empty($data['started_at'])) {
            throw new BadRequestHttpException('game.server.missing_started_at');
        }
        if (empty($data['machine'])) {
            throw new BadRequestHttpException('game.server.missing_machine');
        }
        if (empty($data['factions'])) {
            throw new BadRequestHttpException('game.server.missing_factions');
        }
        return new JsonResponse($this->get(ServerManager::class)->create(
            $data['name'],
            $data['description'],
            'default.jpg',
            $data['started_at'],
            $data['machine'],
            $data['subdomain'] ?? null,
            $data['factions'],
            Server::TYPE_MULTIPLAYER
        ), 201);
    }
    
    /**
     * @Route("/play/{server_id}", name="join_game")
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function joinServerAction(Request $request)
    {
        if (empty($serverId = $request->attributes->get('server_id'))) {
            throw new BadRequestHttpException('game.server.missing_server_id');
        }
        if (($server = $this->get(ServerManager::class)->get($serverId)) === null) {
            throw new NotFoundHttpException('game.server.not_found');
        }
        $jwt = $this->get(ServerManager::class)->joinServer($server, $this->getUser());
        
        return new Response('', Response::HTTP_OK, [
            'Location' => "http://{$server->getHost()}?jwt=$jwt",
        ]);
    }
}