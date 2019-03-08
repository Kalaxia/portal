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

use App\Manager\Game\MachineManager;

class MachineController extends Controller
{
    /**
     * @Route("/admin/machines", name="create_machine", methods={"POST"})
     */
    public function createMachine(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $data = json_decode($request->getContent(), true);

        return new JsonResponse($this->get(MachineManager::class)->create(
            $data['name'],
            (!empty($data['host'])) ? $data['host'] : 'kalaxia_nginx',
            $data['public_key'],
            $data['is_local']
        ), 201);
    }
}
