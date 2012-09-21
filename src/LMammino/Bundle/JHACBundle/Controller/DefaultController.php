<?php

namespace LMammino\Bundle\JHACBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}
