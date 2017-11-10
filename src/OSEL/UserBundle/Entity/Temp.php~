<?php

namespace OSEL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Temp
 *
 * @ORM\Table(name="osel_user_temp")
 * @ORM\Entity(repositoryClass="OSEL\UserBundle\Repository\TempRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Temp
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sha", type="string", length=255, unique=true)
     */
    private $sha;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="text")
     */
    private $role = "setpwd";

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var integer
     *
     * @ORM\Column(name="validity", type="integer")
     */
    private $validity = 172800;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="temps")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(name="count", type="integer")
     */
    private $count = 1;

    public function __construct()
    {
        $this->sha = md5(uniqid(null, true));
        $this->dateAdd = new \DateTime();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sha
     *
     * @param string $sha
     *
     * @return Temp
     */
    public function setSha($sha)
    {
        $this->sha = $sha;

        return $this;
    }

    /**
     * Get sha
     *
     * @return string
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Temp
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Temp
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set validity
     *
     * @param integer $validity
     *
     * @return Temp
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return int
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Temp
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return Temp
     */
    public function setUser(\OSEL\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \OSEL\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return Temp
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @ORM\PreUpdate
     */
    public function increaseCount()
    {
        $this->count++;
    }
}
