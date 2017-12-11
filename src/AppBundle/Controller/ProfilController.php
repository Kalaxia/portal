<?php

    namespace AppBundle\Controller;


    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;

    class ProfilController extends Controller {

        /**
         * @Route("/profil/", name="profil")
         * @Security("has_role('ROLE_USER')")
         */
        public function profilAction(Request $request) {
            return $this->render('front/profil.html.twig', [
                'user' => array(
                    'name' => $this->getUser()->getUsername(),
                    'email' => $this->getUser()->getEmail(),
                    'last_login' => $this->getUser()->getLastLogin(),
                ),
            ]);
        }

    }

?>
