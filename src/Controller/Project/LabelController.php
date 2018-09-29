<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Request
};

use App\Manager\Project\LabelManager;

class LabelController extends Controller
{
    /**
     * @Route("/labels", name="create_label", methods={"POST"})
     */
    public function createAction(Request $request, LabelManager $labelManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (empty($name = $request->request->get('name'))) {
            throw new BadRequestHttpException('project.labels.missing_name');
        }
        if (empty($color = $request->request->get('color'))) {
            throw new BadRequestHttpException('project.labels.missing_color');
        }
        $labelManager->create($name, $color);
        return $this->redirectToRoute('admin_dashboard');
    }
    
    /**
     * @Route("/labels/{id}", name="delete_label", methods={"DELETE"})
     */
    public function deleteAction(Request $request, LabelManager $labelManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.labels.missing_id');
        }
        $labelManager->create($id);
        return $this->redirectToRoute('admin_dashboard');
    }
}