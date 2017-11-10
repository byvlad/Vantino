<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Vant;
use AppBundle\Form\VantFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/vant")
 */
class VantController extends Controller
{
    /**
     * @Route("/new", name="vant_new")
     * @param Request $request
     */
    public function newAction(Request $request)
    {
        $errors = null;
        $form = $this->createForm(VantFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $vant = $form->getData();
            $vant->setUser($this->getUser());

            $validator = $this->get('validator');
            $errors = $validator->validate($vant);

            if($form->isValid()) {
                $file = $request->files->get('image');

                dump($file); die;

                $em = $this->getDoctrine()->getManager();
                $em->persist($vant);
                $em->flush();

                $this->addFlash('success', 'Vant successfully added!');

                return $this->redirectToRoute('profile_page', ['username' => $this->getUser()->getUsername()]);
            }
        }

        return $this->render('user/vant/new.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vant_edit")
     * @param Request $request
     * @param Vant $vant
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Vant $vant)
    {
        if ($this->getUser()->getId() != $vant->getUser()->getId()) {
            throw $this->createNotFoundException(
                'Not found'
            );
        }

        $errors = null;
        $form = $this->createForm(VantFormType::class, $vant);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $vant = $form->getData();
            $vant->setUser($this->getUser());

            $validator = $this->get('validator');
            $errors = $validator->validate($vant);

            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vant);
                $em->flush();

                $this->addFlash('success', 'Vant successfully edited!');

                return $this->redirectToRoute('profile_page', ['username' => $this->getUser()->getUsername()]);
            }
        }

        return $this->render('user/vant/edit.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }

    /**
     * @Route("/{id}/remove", name="vant_remove")
     * @param Request $request
     * @param Vant $vant
     */
    public function removeAction(Request $request, Vant $vant)
    {
        if ($this->getUser()->getId() != $vant->getUser()->getId()) {
            throw $this->createNotFoundException(
                'Not found'
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($vant);
        $em->flush();

        $this->addFlash('success', 'Vant successfully removed!');

        return $this->redirectToRoute('profile_page', ['username' => $this->getUser()->getUsername()]);
    }
}
