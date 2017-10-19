<?php

namespace OSEL\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YearDocument
 *
 * @ORM\Table(name="year_document")
 * @ORM\Entity(repositoryClass="OSEL\DocBundle\Repository\YearDocumentRepository")
 */
class YearDocument
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
     * @ORM\Column(name="year", type="string", length=255, unique=true)
     */
    private $year;

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
     * @ORM\OneToMany(targetEntity="OSEL\DocBundle\Entity\CategoryDocument", mappedBy="year")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\DocBundle\Entity\Document", mappedBy="year")
     * @ORM\JoinColumn(nullable=true)
     */
    private $documents;


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
     * Set year
     *
     * @param string $year
     *
     * @return YearDocument
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
     *
     * @return YearDocument
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
     * @return YearDocument
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
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \OSEL\DocBundle\Entity\Category $category
     *
     * @return YearDocument
     */
    public function addCategory(\OSEL\DocBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \OSEL\DocBundle\Entity\Category $category
     */
    public function removeCategory(\OSEL\DocBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add document
     *
     * @param \OSEL\DocBundle\Entity\Document $document
     *
     * @return YearDocument
     */
    public function addDocument(\OSEL\DocBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \OSEL\DocBundle\Entity\Document $document
     */
    public function removeDocument(\OSEL\DocBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
