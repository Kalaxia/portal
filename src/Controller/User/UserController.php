<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Manager\UserManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="users_list", methods={"GET"})
     */
    public function getAllAction(UserManager $userManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/user/list.html.twig', [
            'users' => $userManager->getAll(),
            'roles' => $this->getParameter('security.role_hierarchy.roles'),
        ]);
    }
    
    /**
     * @Route("/admin/users/search", name="users_search", methods={"POST"})
     */
    public function searchAction(Request $request, UserManager $userManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return new JsonResponse($userManager->search(
            json_decode($request->getContent(), true)
        ));
    }
}