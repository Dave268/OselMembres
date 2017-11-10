<?php

namespace OSEL\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubscribeEvent
 *
 * @ORM\Table(name="osel_event_subscribeevent")
 * @ORM\Entity(repositoryClass="OSEL\EventBundle\Repository\SubscribeEventRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SubscribeEvent
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
     * @ORM\Column(name="transport", type="string", length=255)
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPlaces", type="integer")
     */
    private $nbPlaces;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix = 25;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $paye = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDeparture", type="datetime", nullable=true)
     */
    private $dateDeparture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateArrival", type="datetime", nullable=true)
     */
    private $dateArrival;

    /**
     * @var bool
     *
     * @ORM\Column(name="presence", type="boolean")
     */
    private $presence = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdd", type="datetime")
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdate", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="subscribeEvents")
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\EventBundle\Entity\Event", inversedBy="subscribeEvents")
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\EventBundle\Entity\SubEvents", cascade={"persist"}, inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subEvents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateAdd = new \DateTime();

        $this->subEvents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateUpdate(new \DateTime());
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        foreach ($this->getSubEvents() as $sub)
        {
            $sub->increaseSubscriptions();
        }
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        foreach ($this->getSubEvents() as $sub)
        {
            $sub->decreaseSubscriptions();
        }
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
     * Set transport
     *
     * @param string $transport
     *
     * @return SubscribeEvent
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set dateDeparture
     *
     * @param \DateTime $dateDeparture
     *
     * @return SubscribeEvent
     */
    public function setDateDeparture($dateDeparture)
    {
        $this->dateDeparture = $dateDeparture;

        return $this;
    }

    /**
     * Get dateDeparture
     *
     * @return \DateTime
     */
    public function getDateDeparture()
    {
        return $this->dateDeparture;
    }

    /**
     * Set dateArrivale
     *
     * @param \DateTime $dateArrivale
     *
     * @return SubscribeEvent
     */
    public function setDateArrivale($dateArrivale)
    {
        $this->dateArrivale = $dateArrivale;

        return $this;
    }

    /**
     * Get dateArrivale
     *
     * @return \DateTime
     */
    public function getDateArrivale()
    {
        return $this->dateArrivale;
    }

    /**
     * Set presence
     *
     * @param array $presence
     *
     * @return SubscribeEvent
     */
    public function setPresence($presence)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * Get presence
     *
     * @return array
     */
    public function getPresence()
    {
        return $this->presence;
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return SubscribeEvent
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
     * @return SubscribeEvent
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
     * Set dateArrival
     *
     * @param \DateTime $dateArrival
     *
     * @return SubscribeEvent
     */
    public function setDateArrival($dateArrival)
    {
        $this->dateArrival = $dateArrival;

        return $this;
    }

    /**
     * Get dateArrival
     *
     * @return \DateTime
     */
    public function getDateArrival()
    {
        return $this->dateArrival;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return SubscribeEvent
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
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return SubscribeEvent
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Add subEvent
     *
     * @param \OSEL\EventBundle\Entity\SubEvents $subEvent
     *
     * @return SubscribeEvent
     */
    public function addSubEvent(\OSEL\EventBundle\Entity\SubEvents $subEvent)
    {
        $this->subEvents[] = $subEvent;

        return $this;
    }

    /**
     * Remove subEvent
     *
     * @param \OSEL\EventBundle\Entity\SubEvents $subEvent
     */
    public function removeSubEvent(\OSEL\EventBundle\Entity\SubEvents $subEvent)
    {
        $this->subEvents->removeElement($subEvent);
    }

    /**
     * Get subEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubEvents()
    {
        return $this->subEvents;
    }

    /**
     * Set nbPlaces
     *
     * @param integer $nbPlaces
     *
     * @return SubscribeEvent
     */
    public function setNbPlaces($nbPlaces)
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    /**
     * Get nbPlaces
     *
     * @return integer
     */
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return SubscribeEvent
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set paye
     *
     * @param boolean $paye
     *
     * @return SubscribeEvent
     */
    public function setPaye($paye)
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     * Get paye
     *
     * @return boolean
     */
    public function getPaye()
    {
        return $this->paye;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return SubscribeEvent
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
}
