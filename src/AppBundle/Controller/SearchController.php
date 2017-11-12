<?php

namespace AppBundle\Controller;

use AppBundle\Form\SearchFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 * @package AppBundle\Controller
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/hashtag/{hashtag}", name="search_hashtag")
     * @param $hashtag
     */
    public function hashtagAction($hashtag)
    {
        $formSearch = $this->createForm(SearchFormType::class)->createView();

        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Vant');
        $vants = $em->createQueryBuilder('vant')
            ->where('vant.content LIKE :hashtag')
            ->setParameter('hashtag', '%#'.$hashtag.'%');

        if ($this->isGranted('ROLE_USER')) {
            $vants = $vants->andWhere('vant.type != :type')
                        ->setParameter('type', 'private');
        } else {
            $vants = $vants->andWhere('vant.type = :type')
                        ->setParameter('type', 'public');
        }

        $vants = $vants->getQuery()->execute();

        return $this->render('user/search.html.twig', [
            'hashtag' => '#'.$hashtag,
            'vants' => $vants,
            'formSearch' => $formSearch,
        ]);
    }

    /**
     * @Route("/all", name="search_all")
     */
    public function allAction(Request $request)
    {
        $formSearch = $this->createForm(SearchFormType::class);
        $formSearch->handleRequest($request);
        $query = $formSearch->getData()['slug'];

        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Vant');

        $words = explode(' ', $query);
        $vants = $em->createQueryBuilder('vant');

        if(count($words) > 0) {
            $i = 0;
            $parameters = [];

            foreach ($words as $word) {
                if($word{0} == '#') {
                    // andWhere не работает, использовал orWhere
                    $vants->orWhere('vant.content LIKE ?' . $i++);
                } else {
                    $vants->orWhere('vant.content LIKE ?' . $i++);
                }
                $parameters[] = '%' . $word . '%';
            }
            $vants->setParameters($parameters);
        }

        $vants = $vants->getQuery()->execute();

        return $this->render('user/search.html.twig', [
            'hashtag' => $query,
            'vants' => $vants,
            'formSearch' => $formSearch->createView(),
        ]);
    }
}
