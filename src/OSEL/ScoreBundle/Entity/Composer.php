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
     * @ORM\Column(name="nb_scores", type="integer")
     */
    private $nbScores = 0;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\ScoreBundle\Entity\Score", mappedBy="composer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $scores;


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

    public function increaseScore()
    {
        $this->nbScores++;
    }

    public function decreaseScore()
    {
        $this->nbScores--;
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
}
