<?php

namespace LMammino\Bundle\JHACBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use LMammino\Bundle\JHACBundle\Entity\Cocktail;

class UpdateCocktailsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('jhac:cocktails:update')
            ->setDescription('Load data fixtures to your database.')
            ->setHelp(<<<EOT
The <info>jhac:cocktails:update</info> loads a fixture array of cocktails and load/updates them into the database:

  <info>./app/console jhac:cocktails:update</info>

There are no options for this command.

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $doctrine \Doctrine\Common\Persistence\ManagerRegistry */
        $doctrine = $this->getContainer()->get('doctrine');
        $repository = $doctrine->getRepository('LMamminoJHACBundle:Cocktail');
        $em = $doctrine->getManager();

        $cocktails = require(__DIR__.'/data/cocktails.php');

        $storedCocktails = array();
        foreach($repository->findAll() as $storedCocktail)
            $storedCocktails[$storedCocktail->getSlug()] = $storedCocktail;

        $deleted = 0;
        $updated = 0;
        $created = 0;

        // deletes removed cocktails
        $oldKeys = array_keys($storedCocktails);
        $newKeys = array_keys($cocktails);
        $cocktailsToDeleteKeys = array_diff($oldKeys, $newKeys);
        if(!empty($cocktailsToDeleteKeys))
            foreach($cocktailsToDeleteKeys as $slug)
            {
                $output->writeln(sprintf(' - <error>Deleting</error> "%s"', $slug));
                $em->remove($storedCocktails[$slug]);
                $deleted++;
            }

        foreach($cocktails as $cocktailName => $cocktailData)
        {
            $needUpdate = FALSE;
            $needCreate = FALSE;

            $cocktail = NULL;
            if(isset($storedCocktails[$cocktailName]))
            {
                $cocktail = $storedCocktails[$cocktailName];

                if(
                    $cocktailData['description'] != $cocktail->getDescription() ||
                    $cocktailData['ingredients'] != $cocktail->getIngredients() ||
                    $cocktailData['preparation'] != $cocktail->getPreparation() ||
                    $cocktailData['wikipediaUrl'] != $cocktail->getWikipediaUrl()
                )
                {
                    $output->writeln(sprintf(' - <comment>Updating</comment> "%s"', $cocktailName));
                    $needUpdate = TRUE;
                    $updated++;
                }
            }
            else
            {
                $cocktail = new Cocktail();
                $output->writeln(sprintf(' - <info>Creating</info> "%s"', $cocktailName));
                $needCreate = TRUE;
                $created++;
            }

            if($needUpdate || $needCreate)
            {
                $cocktail->setName($cocktailData['name']);
                $cocktail->setSlug($cocktailName);
                if(!empty($cocktailData['description']))
                    $cocktail->setDescription($cocktailData['description']);
                if(!empty($cocktailData['ingredients']))
                    $cocktail->setIngredients($cocktailData['ingredients']);
                if(!empty($cocktailData['preparation']))
                    $cocktail->setPreparation($cocktailData['preparation']);
                if(!empty($cocktailData['wikipediaUrl']))
                    $cocktail->setWikipediaUrl($cocktailData['wikipediaUrl']);

                $em->persist($cocktail);
            }
        }

        $em->flush();
        $output->writeln(sprintf("\n DONE: <error>%d deleted</error>, <comment>%d updated</comment>, <info>%d created</info>", $deleted, $updated, $created));
    }
}
