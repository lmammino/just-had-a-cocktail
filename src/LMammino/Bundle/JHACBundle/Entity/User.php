<?php

namespace LMammino\Bundle\JHACBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="LMammino\Bundle\JHACBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookAccessToken", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @var Collection $had
     * @ORM\OneToMany(targetEntity="Had", mappedBy="user")
     */
    protected $had;

    public function __construct()
    {
        parent::__construct();
        $this->had = new ArrayCollection();
    }

    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ( $this->getLastname() ?  (' '.$this->getLastname()) : '' );
    }

    /**
     * @param string $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHad()
    {
        return $this->had;
    }

    /**
     * Checks if the user is connected with facebook
     *
     * @return bool
     */
    public function isConnectedWithFacebook()
    {
        return ($this->facebookId !== NULL);
    }

    /**
     * Gets the url of the user profile page on facebook
     *
     * @return string|NULL
     */
    public function getFacebookProfilePageUrl()
    {
        if(!$this->isConnectedWithFacebook())
            return NULL;

        return 'http://www.facebook.com/'.$this->facebookId;
    }

    /**
     * Converts the current user to array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'username' => $this->username,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'facebookId' => $this->facebookId,
            'facebookAccessToken' => $this->facebookAccessToken
        );
    }

}
