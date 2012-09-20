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

        return array
        (
            'cocktails' => $cocktails
        );
    }
}
