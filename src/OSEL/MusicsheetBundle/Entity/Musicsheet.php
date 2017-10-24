<?php

namespace OSEL\MusicsheetBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Musicsheet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OSEL\MusicsheetBundle\Entity\MusicsheetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Musicsheet
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\MusicsheetBundle\Entity\Composer", inversedBy="musicsheets")
     */
    private $composer;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=255)
     */
    private $year;

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
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="musicsheets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="musicsheets_modified")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif = true;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MusicsheetBundle\Entity\Parts", cascade={"persist"}, mappedBy="musicsheet")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parts;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="OSEL\MusicsheetBundle\Entity\SheetType", inversedBy="musicsheets")
     */
    private $type;

    /**
     * @var ArrayCollection
     */
    private $uploadedFiles;


    public function __construct()
    {
        $this->dateAdd  = new \DateTime();
        $this->composer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->type     = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Musicsheet
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
     * Set composer
     *
     * @param string $composer
     * @return Musicsheet
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
     * Set year
     *
     * @param string $year
     * @return Musicsheet
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return Musicsheet
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
     * @return Musicsheet
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
     * Add composer
     *
     * @param \OSEL\MusicsheetBundle\Entity\Composer $composer
     * @return Musicsheet
     */
    public function addComposer(\OSEL\MusicsheetBundle\Entity\Composer $composer)
    {
        $this->composer[] = $composer;

        return $this;
    }

    /**
     * Remove composer
     *
     * @param \OSEL\MusicsheetBundle\Entity\Composer $composer
     */
    public function removeComposer(\OSEL\MusicsheetBundle\Entity\Composer $composer)
    {
        $this->composer->removeElement($composer);
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     * @return Musicsheet
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
     * Set actif
     *
     * @param boolean $actif
     * @return Musicsheet
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Add parts
     *
     * @param \OSEL\MusicsheetBundle\Entity\Parts $parts
     * @return Musicsheet
     */
    public function addPart(\OSEL\MusicsheetBundle\Entity\Parts $parts)
    {
        $this->parts[] = $parts;

        return $this;
    }

    /**
     * Remove parts
     *
     * @param \OSEL\MusicsheetBundle\Entity\Parts $parts
     */
    public function removePart(\OSEL\MusicsheetBundle\Entity\Parts $parts)
    {
        $this->parts->removeElement($parts);
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
     * Set type
     *
     * @param \OSEL\MusicsheetBundle\Entity\SheetType $type
     *
     * @return Musicsheet
     */
    public function setType(\OSEL\MusicsheetBundle\Entity\SheetType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \OSEL\MusicsheetBundle\Entity\SheetType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parts
     *
     * @param \OSEL\MusicsheetBundle\Entity\Parts $parts
     *
     * @return Musicsheet
     */
    public function setParts(\OSEL\MusicsheetBundle\Entity\Parts $parts = null)
    {
        $this->parts = $parts;

        return $this;
    }


    public function upload(){

        $parts = array();

        for($y = 0; $y < sizeof($this->getParts()) ; $y++)
        {
            if($this->getParts()[$y]->getId() == NULL)
            {
                array_push($parts, $this->getParts()[$y]);
                //$parts->add($this->getParts()[$y]);
            }
        }

        for($i = 0; $i < sizeof($this->uploadedFiles); $i++)
        {
            $file = $parts[$i];

            $uploadedFile = $this->uploadedFiles[$i];
            $name = str_replace(" ", "_", $this->getComposer()->getComposer()) . '_' . str_replace(" ", "_", $this->getTitle()) . '_' . str_replace(" ", "_", $file->getInstrument()->getName());
            $dir = scandir($this->getUploadRootDir());

            if($dir)
            {
                for($x = 0; $x < sizeof($dir); $x++)
                {
                    if($dir[$x] == $name . '.' . $uploadedFile->guessExtension())
                    {
                        $name .= '_copy';
                    }
                }
            }


            $file->setUrl('library/' . $this->composer->getComposer() . '/' . $this->getTitle() . '/' . $name . '.' . $uploadedFile->guessExtension());
            $file->setTitle($name . '.' . $uploadedFile->guessExtension());
            $uploadedFile->move($this->getUploadRootDir(), $name . '.' . $uploadedFile->guessExtension());

            $this->addPart($file);
            $file->setMusicsheet($this);

            unset($uploadedFile);
            unset($file);
        }
    }

    protected function getUploadRootDir()
    {
        $directory = __DIR__.'/../../../../web/library/' . $this->composer->getComposer() . '/' . $this->getTitle();
        if(!is_dir($directory))
        {
            if(mkdir($directory))
            {
                if(chmod($directory, 0777))
                {
                    return $directory;
                }
            }
        }
        else
        {
            return $directory;
        }
    }


    public function setUploadedFiles($uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }

    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }



    /**
     * Set lastUser
     *
     * @param \OSEL\UserBundle\Entity\User $lastUser
     *
     * @return Musicsheet
     */
    public function setLastUser(\OSEL\UserBundle\Entity\User $lastUser = null)
    {
        $this->lastUser = $lastUser;

        return $this;
    }

    /**
     * Get lastUser
     *
     * @return \OSEL\UserBundle\Entity\User
     */
    public function getLastUser()
    {
        return $this->lastUser;
    }
}
