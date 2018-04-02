<?php

namespace AppBundle\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Request
};

use Sensio\Bundle\FrameworkExtraBundle\Configuration\{
    Method,
    Route,
    Security
};

use AppBundle\Manager\Project\LabelManager;

class LabelController extends Controller
{
    /**
     * @Route("/labels", name="create_label")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request, LabelManager $labelManager)
    {
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
     * @Route("/labels/{id}", name="delete_label")
     * @Method({"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, LabelManager $labelManager)
    {
        if (empty($id = $request->attributes->get('id'))) {
            throw new BadRequestHttpException('project.labels.missing_id');
        }
        $labelManager->create($id);
        return $this->redirectToRoute('admin_dashboard');
    }
}