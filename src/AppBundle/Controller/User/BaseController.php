<?php

namespace AppBundle\Controller\User;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use AppBundle\Entity\Vant;
use AppBundle\Form\VantFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == false)
            return $this->redirect('/login');

        return $this->redirectToRoute('profile_page', ['username' => $this->getUser()->getUsername()]);
    }

    /**
     * @Route("/profile/{username}", name="profile_page")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function showAction(User $user, Request $request)
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No found'
            );
        }

        if(($user->getId() == $this->getUser()->getId())) {
            if ($request->isMethod('POST')) {

            }
        }

        $vants = $user->getVants();

        $form = $this->createForm(VantFormType::class);

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'vants' => $vants,
            'form' => ($user->getId() == $this->getUser()->getId()) ? $form->createView() : false,
        ]);
    }
}