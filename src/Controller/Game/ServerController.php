<?php

namespace App\Controller\Game;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use App\Manager\Game\{
    FactionManager,
    MachineManager,
    ServerManager
};
use App\Entity\Game\Server;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServerController extends Controller
{
    /**
     * @Route("/servers/{id}", name="server_details", methods={"GET"})
     */
    public function getServer(ServerManager $serverManager, int $id)
    {
        if (($server = $serverManager->get($id)) === null) {
            throw new NotFoundHttpException('servers.not_found');
        }
        return $this->render('game/server/details.html.twig', [
            'server' => $server
        ]);        
    }
    
    /**
     * @Route("/admin/servers/new", name="new_game_server")
     */
    public function newServerAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/game/server/new.html.twig', [
            'factions' => $this->get(FactionManager::class)->getAll(),
            'machines' => $this->get(MachineManager::class)->getAll(),
        ]);
    }
    
    /**
     * @Route("/admin/servers", name="create_game_server", methods={"POST"})
     */
    public function createServerAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
            Server::DEFAULT_BANNER,
            $data['started_at'],
            $data['machine'],
            $data['subdomain'] ?? null,
            $data['factions'],
            Server::TYPE_MULTIPLAYER
        ), 201);
    }
    
    /**
     * @Route("/play/{serverId}", name="join_game", methods={"GET"})
     */
    public function joinServerAction(int $serverId, ServerManager $serverManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (($server = $serverManager->get($serverId)) === null) {
            throw new NotFoundHttpException('game.server.not_found');
        }
        $jwt = $serverManager->joinServer($server, $this->getUser());
        
        return new Response('', Response::HTTP_OK, [
            'Location' => "{$server->getHost()}?jwt=$jwt",
        ]);
    }

    /**
     * @Route("/admin/servers/{id}", name="remove_server", methods={"GET"})
     */
    public function removeServer(int $id, ServerManager $serverManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $serverManager->removeServer($id);

        return new RedirectResponse($this->generateUrl('admin_dashboard'));
    }

    /**
     * @Route("/api/play/{serverId}", name="join_game", methods={"GET"})
     */
    public function joinServerRemotely(int $serverId, ServerManager $serverManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (($server = $serverManager->get($serverId)) === null) {
            throw new NotFoundHttpException('game.server.not_found');
        }
        $jwt = $serverManager->joinServer($server, $this->getUser());
        
        return new JsonResponse([
            'host' => $server->getHost(),
            'token' => $jwt,
        ]);
    }

    /**
     * @Route("/api/servers/me", name="get_player_servers_data", methods={"GET"})
     */
    public function getPlayerServersData()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new JsonResponse($this->getUser()->getServers());
    }

    /**
     * @Route("/api/servers/{id}", name="get_server_data", methods={"GET"})
     */
    public function getServerData(int $id, ServerManager $serverManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (($server = $serverManager->get($id)) === null) {
            throw new NotFoundHttpException('servers.not_found');
        }
        return new JsonResponse($server);
    }
}