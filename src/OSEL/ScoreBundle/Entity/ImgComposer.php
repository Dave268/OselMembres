<?php

namespace OSEL\ScoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * ImgComposer
 *
 * @ORM\Table(name="osel_score_img_composer")
 * @ORM\Entity(repositoryClass="OSEL\ScoreBundle\Repository\ImgComposerRepository")
 */
class ImgComposer
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="originalName", type="string", length=255)
     */
    private $originalName;

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
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif = true;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="composerimgs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="composerimgs_modified")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastUser;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\ScoreBundle\Entity\Composer", inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $composer;

    private $file;

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
     * Set name
     *
     * @param string $name
     *
     * @return ImgComposer
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
     * Set path
     *
     * @param string $path
     *
     * @return ImgComposer
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     *
     * @return ImgComposer
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return ImgComposer
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
     * @return ImgComposer
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
     * Set actif
     *
     * @param boolean $actif
     *
     * @return ImgComposer
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
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return ImgComposer
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
     * @return ImgComposer
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
     * @return ImgComposer
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

    public function uploadImage()
    {
        if (null === $this->file) {
            return;
        }

        $name = md5(uniqid(rand(), true)) .'.'. $this->file->guessExtension();
        $this->name = $name;
        $this->originalName = $this->file->getClientOriginalName();
        $this->path = $this->getUploadDir();

        $this->file->move($this->getUploadRootDir(), $name);

        // On sauvegarde le nom de fichier dans notre attribut $url
        $this->imgPath = $this->getUploadDir() . $name;

    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'library/' . $this->getComposer()->getComposer() . '/';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../web/'.$this->getUploadDir();
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
}
