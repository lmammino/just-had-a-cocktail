<?php

namespace LMammino\Bundle\JHACBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use LMammino\Bundle\JHACBundle\Entity\User;
use LMammino\Bundle\JHACBundle\Facebook\Client;
use LMammino\Bundle\JHACBundle\Entity\Had;

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
     * @Method("GET")
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
     * @Method("GET|POST")
     */
    public function cocktailAction($slug)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $cocktail = $this->getCocktailRepository()->findOneBy(array(
            'slug' => $slug
        ));

        if( !$cocktail instanceof \LMammino\Bundle\JHACBundle\Entity\Cocktail )
            throw $this->createNotFoundException('Cocktail not found');

        $formHadIt = $this->createFormBuilder(array('action' => 'hadIt'))
                          ->add('action', 'hidden')
                          ->getForm();

        $request = $this->getRequest();
        if($request->getMethod() == 'POST')
        {
            $formHadIt->bind($request);
            if( $formHadIt->isValid() &&
                $formHadIt->get('action')->getData() == 'hadIt' &&
                $user instanceof User)
            {
                $em = $this->getDoctrine()->getManager();
                $had = new Had($user, $cocktail);
                $em->persist($had);
                $em->flush();

                if($user->isConnectedWithFacebook())
                {
                    $notification = $this->get('sonata.notification.backend');
                    $notification->createAndPublish('had', $had->toArray());
                }

                // adds data to session for the notice
                $this->get('session')->getFlashBag()->set('notice', sprintf('Now the whole world knows you just had "%s"! ;)', $cocktail->getName()));
                // redirects
                return $this->redirect($this->generateUrl('lmammino_jhac_default_cocktail', array('slug'=> $cocktail->getSlug())));
            }
        }

        return array
        (
            'cocktail' => $cocktail,
            'formHadIt' => $formHadIt->createView()
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
