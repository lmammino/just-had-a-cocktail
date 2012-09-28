<?php

namespace LMammino\Bundle\JHACBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\entity
 * @ORM\Entity(repositoryClass="LMammino\Bundle\JHACBundle\Entity\HadRepository")
 */
class Had
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="User", inversedBy="had")
     */
    private $user;

    /**
     * @var Cocktail $cocktail
     * @ORM\ManyToOne(targetEntity="Cocktail", inversedBy="had")
     */
    private $cocktail;

    /**
     * @var DateTime $date
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    public function __construct(User $user, Cocktail $cocktail, \DateTime $date = NULL)
    {
        $this->user = $user;
        $this->cocktail = $cocktail;
        if($date === NULL)
            $date = new \DateTime();
        $this->date = $date;
    }

    /**
     * @param \LMammino\Bundle\JHACBundle\Entity\Cocktail $cocktail
     */
    public function setCocktail($cocktail)
    {
        $this->cocktail = $cocktail;
    }

    /**
     * @return \LMammino\Bundle\JHACBundle\Entity\Cocktail
     */
    public function getCocktail()
    {
        return $this->cocktail;
    }

    /**
     * @param \LMammino\Bundle\JHACBundle\Entity\DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \LMammino\Bundle\JHACBundle\Entity\DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \LMammino\Bundle\JHACBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \LMammino\Bundle\JHACBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Converts the current entity to array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'user_id' => $this->user->getId(),
            'cocktail_id' => $this->cocktail->getId(),
            'date' => $this->date->getTimestamp()
        );
    }
}
