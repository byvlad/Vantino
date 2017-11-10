<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use AppBundle\Form\SearchFormType;
use AppBundle\Form\VantFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if ($this->isGranted('ROLE_USER') == false)
            return $this->redirect('/login');

        return $this->redirectToRoute('profile_page', ['username' => $this->getUser()->getUsername()]);
    }

    /**
     * @Route("/profile/{username}", name="profile_page")
     * @param User $user
     * @return Response
     */
    public function showAction(User $user)
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'Not found'
            );
        }

        $form = false;
        $formSearch = $this->createForm(SearchFormType::class)->createView();

        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Vant');
        if ($this->isGranted('ROLE_USER')) {
            if($user->getId() == $this->getUser()->getId()) {
                $vants = $user->getVants();
                $form = $this->createForm(VantFormType::class)->createView();
            } else {
                $vants = $em->findBy(['user' => $user, 'type !=' => 'private']);
            }
        } else {
            $vants = $em->findBy(['user' => $user, 'type' => 'public']);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'vants' => $vants,
            'form' => $form,
            'formSearch' => $formSearch,
        ]);
    }
}