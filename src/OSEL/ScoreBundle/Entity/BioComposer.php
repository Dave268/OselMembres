<?php

namespace OSEL\ScoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BioComposer
 *
 * @ORM\Table(name="osel_score_bio_composer")
 * @ORM\Entity(repositoryClass="OSEL\ScoreBundle\Repository\BioComposerRepository")
 */
class BioComposer
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="composerbios")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="composerbios_modified")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastUser;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\ScoreBundle\Entity\Composer", inversedBy="bios")
     * @ORM\JoinColumn(nullable=true)
     */
    private $composer;

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
     * Set content
     *
     * @param string $content
     *
     * @return BioComposer
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return BioComposer
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
     *
     * @return BioComposer
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
     * @return BioComposer
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
     * @return BioComposer
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
     * @return BioComposer
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
     * Set composer
     *
     * @param \OSEL\ScoreBundle\Entity\Composer $composer
     *
     * @return BioComposer
     */
    public function setComposer(\OSEL\ScoreBundle\Entity\Composer $composer = null)
    {
        $this->composer = $composer;

        return $this;
    }

    /**
     * Get composer
     *
     * @return \OSEL\ScoreBundle\Entity\Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }
}
