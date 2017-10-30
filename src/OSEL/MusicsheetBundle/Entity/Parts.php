<?php

namespace OSEL\MusicsheetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Parts
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OSEL\MusicsheetBundle\Entity\PartsRepository")
 */
class Parts
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

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
     * @ORM\ManyToOne(targetEntity="OSEL\MusicsheetBundle\Entity\Musicsheet", inversedBy="parts")
     */
    private $musicsheet;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\MusicsheetBundle\Entity\Instrument", inversedBy="parts")
     */
    private $instrument;


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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Parts
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
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return Parts
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
     * @return Parts
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
     * Set part
     *
     * @param string $part
     * @return Parts
     */
    public function setPart($part)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return string
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Set musicsheet
     *
     * @param \OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheet
     * @return Parts
     */
    public function setMusicsheet(\OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheet = null)
    {
        $this->musicsheet = $musicsheet;

        return $this;
    }

    /**
     * Get musicsheet
     *
     * @return \OSEL\MusicsheetBundle\Entity\Musicsheet
     */
    public function getMusicsheet()
    {
        return $this->musicsheet;
    }


    /**
     * Set instrument
     *
     * @param \OSEL\MusicsheetBundle\Entity\Instrument $instrument
     *
     * @return Parts
     */
    public function setInstrument(\OSEL\MusicsheetBundle\Entity\Instrument $instrument = null)
    {
        $this->instrument = $instrument;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return \OSEL\MusicsheetBundle\Entity\Instrument
     */
    public function getInstrument()
    {
        return $this->instrument;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Parts
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}
