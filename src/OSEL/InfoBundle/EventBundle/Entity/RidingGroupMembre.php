<?php

namespace OSEL\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RidingGroupMembre
 *
 * @ORM\Table(name="riding_group_membre")
 * @ORM\Entity(repositoryClass="OSEL\EventBundle\Repository\RidingGroupMembreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RidingGroupMembre
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
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="ridingGroupMembres")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\EventBundle\Entity\RidingGroup", inversedBy="membres")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ridingGroup;


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
     * Set status
     *
     * @param string $status
     *
     * @return RidingGroupMembre
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return RidingGroupMembre
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return bool
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return RidingGroupMembre
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
     * Set ridingGroup
     *
     * @param \OSEL\EventBundle\Entity\RidingGroup $ridingGroup
     *
     * @return RidingGroupMembre
     */
    public function setRidingGroup(\OSEL\EventBundle\Entity\RidingGroup $ridingGroup = null)
    {
        $this->ridingGroup = $ridingGroup;

        return $this;
    }

    /**
     * Get ridingGroup
     *
     * @return \OSEL\EventBundle\Entity\RidingGroup
     */
    public function getRidingGroup()
    {
        return $this->ridingGroup;
    }
}
