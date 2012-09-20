<?php

namespace LMammino\Bundle\JHACBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LMammino\Bundle\JHACBundle\Entity\Cocktail;

class LoadCocktailData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $cocktails = require(__DIR__.'/../data/cocktails.php');

        foreach($cocktails as $cocktailName => $cocktailData)
        {
            $cocktail = new Cocktail();
            $cocktail->setName($cocktailData['name']);
            $cocktail->setSlug($cocktailName);
            if(!empty($cocktailData['description']))
                $cocktail->setDescription($cocktailData['description']);
            if(!empty($cocktailData['calories']))
                $cocktail->setCalories($cocktailData['calories']);

            $manager->persist($cocktail);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}