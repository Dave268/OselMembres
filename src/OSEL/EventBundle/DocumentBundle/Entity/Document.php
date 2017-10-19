<?php

namespace OSEL\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="OSEL\DocBundle\Repository\DocumentRepository")
 */
class Document
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    private $url;

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
     * @ORM\ManyToOne(targetEntity="OSEL\DocBundle\Entity\CategoryDocument", inversedBy="documents")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\DocBundle\Entity\YearDocument", inversedBy="documents")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\UserBundle\Entity\User", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\Roles", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $role;


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
     * @return Document
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
     * Set url
     *
     * @param string $url
     *
     * @return Document
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

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Document
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
     * @return Document
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
     * Set category
     *
     * @param \OSEL\DocBundle\Entity\Category $category
     *
     * @return Document
     */
    public function setCategory(\OSEL\DocBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \OSEL\DocBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return Document
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
     * Set role
     *
     * @param \OSEL\UserBundle\Entity\Roles $role
     *
     * @return Document
     */
    public function setRole(\OSEL\UserBundle\Entity\Roles $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \OSEL\UserBundle\Entity\Roles
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set year
     *
     * @param \OSEL\DocBundle\Entity\YearDocument $year
     *
     * @return Document
     */
    public function setYear(\OSEL\DocBundle\Entity\YearDocument $year = null)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \OSEL\DocBundle\Entity\YearDocument
     */
    public function getYear()
    {
        return $this->year;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add role
     *
     * @param \OSEL\UserBundle\Entity\Roles $role
     *
     * @return Document
     */
    public function addRole(\OSEL\UserBundle\Entity\Roles $role)
    {
        $this->role[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \OSEL\UserBundle\Entity\Roles $role
     */
    public function removeRole(\OSEL\UserBundle\Entity\Roles $role)
    {
        $this->role->removeElement($role);
    }
}
