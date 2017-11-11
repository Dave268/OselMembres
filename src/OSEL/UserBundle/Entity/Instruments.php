<?php

namespace OSEL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instruments
 *
 * @ORM\Table(name="osel_user_instruments")
 * @ORM\Entity(repositoryClass="OSEL\UserBundle\Entity\InstrumentsRepository")
 */
class Instruments
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="instrument", type="string", length=255)
     */
    private $instrument;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\User", mappedBy="instruments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;


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
     * Set instrument
     *
     * @param string $instrument
     * @return Instruments
     */
    public function setInstrument($instrument)
    {
        $this->instrument = $instrument;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return string 
     */
    public function getInstrument()
    {
        return $this->instrument;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \OSEL\UserBundle\Entity\User $users
     * @return Instruments
     */
    public function addUser(\OSEL\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \OSEL\UserBundle\Entity\User $users
     */
    public function removeUser(\OSEL\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
