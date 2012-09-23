<?php

namespace LMammino\Bundle\JHACBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * Get the cocktail repository
     *
     * @return \LMammino\Bundle\JHACBundle\Entity\CocktailRepository
     */
    protected function getCocktailRepository()
    {
        return $this->getDoctrine()->getRepository('LMamminoJHACBundle:Cocktail');
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $cocktails = $this->getCocktailRepository()->findCocktails();
        $cocktailOfTheDay = $cocktails[(int)date('z') % count($cocktails)];

        return array
        (
            'cocktails' => $cocktails,
            'cocktailOfTheDay' => $cocktailOfTheDay
        );
    }

    /**
     * @Route("/cocktail/{slug}")
     * @Template()
     */
    public function cocktailAction($slug)
    {
        $cocktail = $this->getCocktailRepository()->findOneBy(array(
            'slug' => $slug
        ));

        if( !$cocktail instanceof \LMammino\Bundle\JHACBundle\Entity\Cocktail )
            throw $this->createNotFoundException('Cocktail not found');

        return array
        (
            'cocktail' => $cocktail
        );
    }


    /*
    * Dummy controller for FB login
    */
    public function loginCheckAction()
    {
        //intercepted by user provider
        return new Response();
    }

    /*
    * Dummy controller for FB login
    */
    public function logoutAction()
    {
        //intercepted by user provider
        return new Response();
    }

    /*
    * FB login errors view
    */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return new Response($error);
    }
}
