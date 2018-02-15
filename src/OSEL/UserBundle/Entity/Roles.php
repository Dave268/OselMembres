<?php

namespace OSEL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Roles
 *
 * @ORM\Table(name="osel_user_roles")
 * @ORM\Entity(repositoryClass="OSEL\UserBundle\Entity\RolesRepository")
 */
class Roles extends Role
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
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\User", mappedBy="userRoles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\DocumentBundle\Entity\Directory", mappedBy="role")
     * @ORM\JoinColumn(nullable=true)
     */
    private $directories;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\DocumentBundle\Entity\File", mappedBy="role")
     * @ORM\JoinColumn(nullable=true)
     */
    private $files;

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
     * Set role
     *
     * @param string $role
     * @return Roles
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     * @return Roles
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
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     *
     * @return Roles
     */
    public function addUser(\OSEL\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \OSEL\UserBundle\Entity\User $user
     */
    public function removeUser(\OSEL\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Roles
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
     * Add directory
     *
     * @param \OSEL\DocumentBundle\Entity\Directory $directory
     *
     * @return Roles
     */
    public function addDirectory(\OSEL\DocumentBundle\Entity\Directory $directory)
    {
        $this->directories[] = $directory;

        return $this;
    }

    /**
     * Remove directory
     *
     * @param \OSEL\DocumentBundle\Entity\Directory $directory
     */
    public function removeDirectory(\OSEL\DocumentBundle\Entity\Directory $directory)
    {
        $this->directories->removeElement($directory);
    }

    /**
     * Get directories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * Add file
     *
     * @param \OSEL\DocumentBundle\Entity\File $file
     *
     * @return Roles
     */
    public function addFile(\OSEL\DocumentBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \OSEL\DocumentBundle\Entity\File $file
     */
    public function removeFile(\OSEL\DocumentBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return Roles
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }
}
