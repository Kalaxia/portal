<?php

namespace AppBundle\Controller\Game;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use AppBundle\Manager\Game\MachineManager;

class MachineController extends Controller
{    
    /**
     * @Route("/admin/machines", name="create_machine", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createMachine(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        return new JsonResponse($this->get(MachineManager::class)->create(
            $data['name'],
            (!empty($data['host'])) ? $data['host'] : 'http://kalaxia_nginx',
            $data['public_key'],
            $data['is_local']
        ), 201);
    }
}