<?php

namespace OSEL\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RidingGroup
 *
 * @ORM\Table(name="riding_group")
 * @ORM\Entity(repositoryClass="OSEL\EventBundle\Repository\RidingGroupRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RidingGroup
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="infos", type="text", nullable=true)
     */
    private $infos;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="ridingGroups")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\EventBundle\Entity\Event", inversedBy="ridingGroups")
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\EventBundle\Entity\RidingGroupMembre", cascade={"persist"}, mappedBy="ridingGroup")
     * @ORM\JoinColumn(nullable=true)
     */
    private $membres;


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
     * Set title
     *
     * @param string $title
     *
     * @return RidingGroup
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set infos
     *
     * @param string $infos
     *
     * @return RidingGroup
     */
    public function setInfos($infos)
    {
        $this->infos = $infos;

        return $this;
    }

    /**
     * Get infos
     *
     * @return string
     */
    public function getInfos()
    {
        return $this->infos;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return RidingGroup
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
     * Set event
     *
     * @param \OSEL\EventBundle\Entity\Event $event
     *
     * @return RidingGroup
     */
    public function setEvent(\OSEL\EventBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \OSEL\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Add membre
     *
     * @param \OSEL\EventBundle\Entity\RidingGroupMembre $membre
     *
     * @return RidingGroup
     */
    public function addMembre(\OSEL\EventBundle\Entity\RidingGroupMembre $membre)
    {
        $this->membres[] = $membre;

        return $this;
    }

    /**
     * Remove membre
     *
     * @param \OSEL\EventBundle\Entity\RidingGroupMembre $membre
     */
    public function removeMembre(\OSEL\EventBundle\Entity\RidingGroupMembre $membre)
    {
        $this->membres->removeElement($membre);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembres()
    {
        return $this->membres;
    }
}
