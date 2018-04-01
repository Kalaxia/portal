<?php

namespace AppBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Manager\UserManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="users_list", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getAllAction(UserManager $userManager)
    {
        return $this->render('admin/user/list.html.twig', [
            'users' => $userManager->getAll(),
            'roles' => $this->getParameter('security.role_hierarchy.roles'),
        ]);
    }
    
    /**
     * @Route("/admin/users/search", name="users_search", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function searchAction(Request $request, UserManager $userManager)
    {
        return new JsonResponse($userManager->search(
            json_decode($request->getContent(), true)
        ));
    }
}