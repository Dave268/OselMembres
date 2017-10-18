<?php

namespace OSEL\MusicsheetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MusicsheetType
 *
 * @ORM\Table(name="sheet_type")
 * @ORM\Entity(repositoryClass="OSEL\MusicsheetBundle\Repository\SheetTypeRepository")
 */
class SheetType
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
     * @ORM\OneToMany(targetEntity="OSEL\MusicsheetBundle\Entity\Musicsheet", mappedBy="type")
     * @ORM\JoinColumn(nullable=true)
     */
    private $musicsheets;


    public function __construct()
    {
        $this->dateAdd = new \DateTime();
        $this->musicsheets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SheetType
     */
    public function setTitle($title)
    {
        if($temp = explode(' ', $title))
        {
            for($i = 0; $i < count($temp) ; $i++)
            {
                $temp[$i] = ucfirst(strtolower($temp[$i]));
            }
            $this->title = implode(' ', $temp);
        }
        else
        {
            $this->title = ucfirst(strtolower($title));
        }


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
     *
     * @return SheetType
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
     * @return SheetType
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
     * Add musicsheet
     *
     * @param \OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheet
     *
     * @return SheetType
     */
    public function addMusicsheet(\OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheet)
    {
        $this->musicsheets[] = $musicsheet;

        return $this;
    }

    /**
     * Remove musicsheet
     *
     * @param \OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheet
     */
    public function removeMusicsheet(\OSEL\MusicsheetBundle\Entity\Musicsheet $musicsheet)
    {
        $this->musicsheets->removeElement($musicsheet);
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
}
