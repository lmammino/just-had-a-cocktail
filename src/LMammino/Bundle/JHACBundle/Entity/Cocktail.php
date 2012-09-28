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
     * @var array $ingredients
     *
     * @ORM\Column(name="ingredients", type="array", nullable=true)
     */
    private $ingredients;

    /**
     * @var string $preparation
     *
     * @ORM\Column(name="preparation", type="text", nullable=true)
     */
    private $preparation;

    /**
     * @var string $wikipediaUrl
     *
     * @ORM\Column(name="wikipediaUrl", type="text", nullable=true)
     */
    private $wikipediaUrl;


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
     * @param array $ingredients
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return array
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Get ingredients list as string
     *
     * @param bool $showQuantities
     * @return string
     */
    public function getIngredientsList($showQuantities = false)
    {
        $list = array();
        foreach($this->ingredients as $ingredient => $quantity)
        {
            if($showQuantities)
                $list[] = sprintf('%s (%s)', $ingredient, $quantity);
            else
                $list[] = $ingredient;
        }
        return implode(', ', $list);
    }

    /**
     * @param string $preparation
     */
    public function setPreparation($preparation)
    {
        $this->preparation = $preparation;
    }

    /**
     * @return string
     */
    public function getPreparation()
    {
        return $this->preparation;
    }

    /**
     * @param string $wikipediaUrl
     */
    public function setWikipediaUrl($wikipediaUrl)
    {
        $this->wikipediaUrl = $wikipediaUrl;
    }

    /**
     * @return string
     */
    public function getWikipediaUrl()
    {
        return $this->wikipediaUrl;
    }

    /**
     * Get the relative path of the cocktail image
     *
     * @return string
     */
    public function getImageSrc($variant = NULL)
    {
        return \LMammino\Bundle\JHACBundle\LMamminoJHACBundle::getImageDir() .
                    $this->slug . '-cocktail' . (($variant) ? ('-' . $variant) : '') . '.png';
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

    /**
     * Truncates the description
     *
     * @return string
     */
    public function computeShortDescription()
    {
        return substr($this->description, 0, 155);
    }

    /**
     * Return a string containing the keywords of the current cocktail
     *
     * @param string $separator
     * @return string
     */
    public function computeKeywords($separator = ', ')
    {
        $keywords = array($this->name);
        $keywords = array_merge($keywords, array_keys($this->ingredients));
        return implode($separator, $keywords);
    }

    /**
     * Converts the current entity to array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'relativeUrl' => $this->getRelativeUrl()
        );
    }
}
