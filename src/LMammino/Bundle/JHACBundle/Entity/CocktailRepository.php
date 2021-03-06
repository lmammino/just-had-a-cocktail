<?php

namespace LMammino\Bundle\JHACBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CocktailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CocktailRepository extends EntityRepository
{

    public function findCocktails($limit = NULL, $firstResult = 0, $orderBy = 'slug ASC')
    {
        $dql = sprintf
        (
            'SELECT
              c
            FROM LMamminoJHACBundle:Cocktail c
            ORDER BY c.%s',
            $orderBy
        );

        $query = $this->_em->createQuery($dql);

        if($limit !== NULL)
        {
            $query->setMaxResults($limit)
                ->setFirstResult($firstResult);
            return new Paginator($query, false);
        }

        return $query->getResult();
    }
}
