<?php

namespace LMammino\Bundle\JHACBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LMammino\Bundle\JHACBundle\Entity\Cocktail
 *
 * @ORM\Table(
 *      uniqueConstraints={ @ORM\UniqueConstraint(name="slug_unique",columns={"slug"}) }
 * )
 * @ORM\Entity(repositoryClass="LMammino\Bundle\JHACBundle\Entity\CocktailRepository")
 */
class Cocktail
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer $calories
     *
     * @ORM\Column(name="calories", type="integer", nullable=true)
     */
    private $calories;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Cocktail
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Cocktail
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Cocktail
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set calories
     *
     * @param integer $calories
     * @return Cocktail
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;
    
        return $this;
    }

    /**
     * Get calories
     *
     * @return integer 
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * Get the relative path of the cocktail image
     *
     * @return string
     */
    public function getImageSrc()
    {
        return \LMammino\Bundle\JHACBundle\LMamminoJHACBundle::getImageDir() . $this->slug . '-cocktail.png';
    }

    /**
     * Get the cocktail url
     *
     * @return string
     */
    public function getRelativeUrl()
    {
        return '/cocktail/'. $this->slug;
    }
}
