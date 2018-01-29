<?php

namespace OSEL\ScoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Composer
 *
 * @ORM\Table(name="osel_score_composer")
 * @ORM\Entity(repositoryClass="OSEL\ScoreBundle\Repository\ComposerRepository")
 * @ORM\HasLifecycleCallbacks()
 *
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
     * @var string
     *
     * @ORM\Column(name="date_birth", type="string", length=255, nullable=true)
     */
    private $dateBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="date_death", type="string", length=255, nullable=true)
     */
    private $dateDeath;

    /**
     * @ORM\Column(name="nb_scores", type="integer")
     */
    private $nbScores = 0;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\ScoreBundle\Entity\Score", mappedBy="composer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $scores;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif = false;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="composers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="composers_modified")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastUser;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\ScoreBundle\Entity\BioComposer", mappedBy="composer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bios;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\ScoreBundle\Entity\ImgComposer", mappedBy="composer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $images;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scores= new ArrayCollection();
        $this->setComposer($this->getName() . $this->getLastName());
        $this->dateAdd = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateUpdate(new \DateTime());
    }


    public function increaseScore()
    {
        $this->nbScores++;

        if($this->nbScores > 0)
        {
            $this->actif = true;
        }
    }

    public function decreaseScore()
    {

        $this->nbScores--;

        if($this->nbScores == 0)
        {
            $this->actif = false;
        }
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
     *
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
     * Set name
     *
     * @param string $name
     *
     * @return Composer
     */
    public function setName($name)
    {
        $this->name = $name;

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
        $this->lastName = $lastName;

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

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
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
     *
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
     * @param string $dateBirth
     *
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
     * @return string
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * Set dateDeath
     *
     * @param string $dateDeath
     *
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
     * @return string
     */
    public function getDateDeath()
    {
        return $this->dateDeath;
    }

    /**
     * Set nbScores
     *
     * @param integer $nbScores
     *
     * @return Composer
     */
    public function setNbScores($nbScores)
    {
        $this->nbScores = $nbScores;

        return $this;
    }

    /**
     * Get nbScores
     *
     * @return integer
     */
    public function getNbScores()
    {
        return $this->nbScores;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Composer
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
     * Add score
     *
     * @param \OSEL\ScoreBundle\Entity\Score $score
     *
     * @return Composer
     */
    public function addScore(\OSEL\ScoreBundle\Entity\Score $score)
    {
        $this->scores[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param \OSEL\ScoreBundle\Entity\Score $score
     */
    public function removeScore(\OSEL\ScoreBundle\Entity\Score $score)
    {
        $this->scores->removeElement($score);
    }

    /**
     * Get scores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return Composer
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
     * Set lastUser
     *
     * @param \OSEL\UserBundle\Entity\User $lastUser
     *
     * @return Composer
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

    /**
     * Add bio
     *
     * @param \OSEL\ScoreBundle\Entity\BioComposer $bio
     *
     * @return Composer
     */
    public function addBio(\OSEL\ScoreBundle\Entity\BioComposer $bio)
    {
        $this->bios[] = $bio;

        return $this;
    }

    /**
     * Remove bio
     *
     * @param \OSEL\ScoreBundle\Entity\BioComposer $bio
     */
    public function removeBio(\OSEL\ScoreBundle\Entity\BioComposer $bio)
    {
        $this->bios->removeElement($bio);
    }

    /**
     * Get bios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBios()
    {
        return $this->bios;
    }

    /**
     * Add image
     *
     * @param \OSEL\ScoreBundle\Entity\ImgComposer $image
     *
     * @return Composer
     */
    public function addImage(\OSEL\ScoreBundle\Entity\ImgComposer $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \OSEL\ScoreBundle\Entity\ImgComposer $image
     */
    public function removeImage(\OSEL\ScoreBundle\Entity\ImgComposer $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
