<?php

namespace OSEL\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubEvents
 *
 * @ORM\Table(name="osel_event_subevents")
 * @ORM\Entity(repositoryClass="OSEL\EventBundle\Repository\SubEventsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SubEvents
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
     * @var \DateTime
     *
     * @ORM\Column(name="startEvent", type="datetime", nullable=true)
     */
    private $startEvent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stopEvent", type="datetime", nullable=true)
     */
    private $stopEvent;

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
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="subEvents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\EventBundle\Entity\Event", cascade={"persist"}, inversedBy="subEvents")
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\EventBundle\Entity\SubscribeEvent", cascade={"persist"}, mappedBy="subEvents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subscriptions;

    /**
     * @ORM\Column(name="nb_subscriptions", type="integer")
     */
    private $nbSubscriptions = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateAdd = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateUpdate(new \DateTime());
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
     * Set title
     *
     * @param string $title
     *
     * @return SubEvents
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
     * Set info
     *
     * @param string $info
     *
     * @return SubEvents
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set dateEvent
     *
     * @param \DateTime $dateEvent
     *
     * @return SubEvents
     */
    public function setDateEvent($dateEvent)
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    /**
     * Get dateEvent
     *
     * @return \DateTime
     */
    public function getDateEvent()
    {
        return $this->dateEvent;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return SubEvents
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
     * @return SubEvents
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
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return SubEvents
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
     * @return SubEvents
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
     * Set startEvent
     *
     * @param \DateTime $startEvent
     *
     * @return SubEvents
     */
    public function setStartEvent($startEvent)
    {
        $this->startEvent = $startEvent;

        return $this;
    }

    /**
     * Get startEvent
     *
     * @return \DateTime
     */
    public function getStartEvent()
    {
        return $this->startEvent;
    }

    /**
     * Set stopEvent
     *
     * @param \DateTime $stopEvent
     *
     * @return SubEvents
     */
    public function setStopEvent($stopEvent)
    {
        $this->stopEvent = $stopEvent;

        return $this;
    }

    /**
     * Get stopEvent
     *
     * @return \DateTime
     */
    public function getStopEvent()
    {
        return $this->stopEvent;
    }

    /**
     * Add subscription
     *
     * @param \OSEL\EventBundle\Entity\SubscribeEvent $subscription
     *
     * @return SubEvents
     */
    public function addSubscription(\OSEL\EventBundle\Entity\SubscribeEvent $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param \OSEL\EventBundle\Entity\SubscribeEvent $subscription
     */
    public function removeSubscription(\OSEL\EventBundle\Entity\SubscribeEvent $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Set nbSubscriptions
     *
     * @param integer $nbSubscriptions
     *
     * @return SubEvents
     */
    public function setNbSubscriptions($nbSubscriptions)
    {
        $this->nbSubscriptions = $nbSubscriptions;

        return $this;
    }

    /**
     * Get nbSubscriptions
     *
     * @return integer
     */
    public function getNbSubscriptions()
    {
        return $this->nbSubscriptions;
    }

    public function increaseSubscriptions()
    {
        $this->nbSubscriptions++;
    }

    public function decreaseSubscriptions()
    {
        $this->nbSubscriptions--;

    }
}
