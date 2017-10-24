<?php

namespace OSEL\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryDocument
 *
 * @ORM\Table(name="category_document")
 * @ORM\Entity(repositoryClass="OSEL\DocBundle\Repository\CategoryDocumentRepository")
 */
class CategoryDocument
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
     * @ORM\Column(name="Category", type="string", length=255, unique=true)
     */
    private $category;

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
     * @ORM\OneToMany(targetEntity="OSEL\DocBundle\Entity\Document", cascade={"persist"}, mappedBy="category")
     * @ORM\JoinColumn(nullable=true)
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity="OSEL\DocBundle\Entity\YearDocument", inversedBy="categories")
     */
    private $year;


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
     * Set category
     *
     * @param string $category
     *
     * @return CategoryDocument
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return CategoryDocument
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
     * @return CategoryDocument
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
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add document
     *
     * @param \OSEL\DocBundle\Entity\Document $document
     *
     * @return CategoryDocument
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

    /**
     * Set year
     *
     * @param \OSEL\DocBundle\Entity\YearDocument $year
     *
     * @return CategoryDocument
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
}
