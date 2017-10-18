<?php

namespace OSEL\MusicsheetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Composer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OSEL\MusicsheetBundle\Entity\ComposerRepository")
 */
class Composer
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
     * @ORM\Column(name="composer", type="string", length=255, unique=true)
     */
    private $composer;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;


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
     * @var \DateTime
     *
     * @ORM\Column(name="date_birth", type="date", nullable=true)
     */
    private $dateBirth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_death", type="date", nullable=true)
     */
    private $dateDeath;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MusicsheetBundle\Entity\Musicsheet", mappedBy="composer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $musicsheets;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->musicsheets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setComposer($this->getName() . $this->getLastName());
        $this->dateAdd = new \DateTime();
    }


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
     * Set composer
     *
     * @param string $composer
     * @return Composer
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;

        return $this;
    }

    /**
     * Get composer
     *
     * @return string
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return Composer
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
     * @return Composer
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
     * Set dateBirth
     *
     * @param \DateTime $dateBirth
     * @return Composer
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return \DateTime
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * Set dateDeath
     *
     * @param \DateTime $dateDeath
     * @return Composer
     */
    public function setDateDeath($dateDeath)
    {
        $this->dateDeath = $dateDeath;

        return $this;
    }

    /**
     * Get dateDeath
     *
     * @return \DateTime
     */
    public function getDateDeath()
    {
        return $this->dateDeath;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateUpdate(new \DateTime());
    }

    /**
     * Add musicsheets
     *
     * @param \OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheets
     * @return Composer
     */
    public function addMusicsheet(\OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheets)
    {
        $this->musicsheets[] = $musicsheets;

        return $this;
    }

    /**
     * Remove musicsheets
     *
     * @param \OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheets
     */
    public function removeMusicsheet(\OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheets)
    {
        $this->musicsheets->removeElement($musicsheets);
    }

    /**
     * Get musicsheets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMusicsheets()
    {
        return $this->musicsheets;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Composer
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Composer
     */
    public function setLastName($lastName)
    {
        if($temp = explode(' ', $lastName))
        {
            for($i = 0; $i < count($temp) ; $i++)
            {
                $temp[$i] = ucfirst(strtolower($temp[$i]));
            }
            $this->lastName = implode(' ', $temp);
        }
        else
        {
            $this->lastName = ucfirst(strtolower($lastName));
        }




        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
