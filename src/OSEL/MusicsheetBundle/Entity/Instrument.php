<?php

namespace OSEL\MusicsheetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instrument
 *
 * @ORM\Table(name="instrument")
 * @ORM\Entity(repositoryClass="OSEL\MusicsheetBundle\Repository\InstrumentRepository")
 */
class Instrument
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MusicsheetBundle\Entity\Parts", mappedBy="instrument")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parts;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\User", mappedBy="instrumentMusicsheets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;


    public function __construct()
    {
        $this->dateAdd = new \DateTime();
        $this->parts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Instrument
     */
    public function setName($name)
    {
        if($temp = explode(' ', $name))
        {
            for($i = 0; $i < count($temp) ; $i++)
            {
                $temp[$i] = ucfirst(strtolower($temp[$i]));
            }
            $this->name = implode(' ', $temp);
        }
        else
        {
            $this->name = ucfirst(strtolower($name));
        }

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
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Instrument
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
     * @return Instrument
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
     * Add part
     *
     * @param \OSEL\MusicsheetBundle\Entity\Parts $part
     *
     * @return Instrument
     */
    public function addPart(\OSEL\MusicsheetBundle\Entity\Parts $part)
    {
        $this->parts[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \OSEL\MusicsheetBundle\Entity\Parts $part
     */
    public function removePart(\OSEL\MusicsheetBundle\Entity\Parts $part)
    {
        $this->parts->removeElement($part);
    }

    /**
     * Get parts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Add user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return Instrument
     */
    public function addUser(\OSEL\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     */
    public function removeUser(\OSEL\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
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
