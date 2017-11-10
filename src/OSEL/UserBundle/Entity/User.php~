<?php

namespace OSEL\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="osel_user_user")
 * @ORM\Entity(repositoryClass="OSEL\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     *
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\Roles", inversedBy="users")
     */
    private $userRoles;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\Instruments", inversedBy="users", cascade={"persist"})
     */
    private $instruments;

    /**
     * @ORM\ManyToMany(targetEntity="OSEL\UserBundle\Entity\Groupes", cascade={"persist"})
     */
    private $groupes;

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="postal", type="string", length=255, nullable=true)
     */
    private $postal;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobilephone", type="string", length=255, nullable=true)
     */
    private $mobilephone;

    /**
     * @var string
     *
     * @ORM\Column(name="emergencylastname", type="string", length=255, nullable=true)
     */
    private $emergencylastname;

    /**
     * @var string
     *
     * @ORM\Column(name="emergencyname", type="string", length=255, nullable=true)
     */
    private $emergencyname;

    /**
     * @var string
     *
     * @ORM\Column(name="emergencyrelation", type="string", length=255, nullable=true)
     */
    private $emergencyrelation;

    /**
     * @var string
     *
     * @ORM\Column(name="emergencyphone", type="string", length=255, nullable=true)
     */
    private $emergencyphone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

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
     * @var boolean
     *
     * @ORM\Column(name="newsletter", type="boolean", nullable=true)
     */
    private $newsletter = true;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\UserBundle\Entity\Login", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $logins;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\NewsBundle\Entity\News", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\NewsBundle\Entity\ImageNews", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $imageNews;


    /**
     * @ORM\OneToMany(targetEntity="OSEL\ScoreBundle\Entity\Score", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $scores;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\ScoreBundle\Entity\Score", mappedBy="lastUser")
     * @ORM\JoinColumn(nullable=true)
     */
    private $scores_modified;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\UserBundle\Entity\Temp", cascade={"persist"}, mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $temps;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\EventBundle\Entity\Event", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\EventBundle\Entity\RidingGroup", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ridingGroups;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\EventBundle\Entity\RidingGroupMembre", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ridingGroupMembres;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\EventBundle\Entity\SubEvents", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subEvents;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\EventBundle\Entity\SubscribeEvent", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subscribeEvents;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MediaBundle\Entity\YearMedia", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $yearMedias;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MediaBundle\Entity\Album", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $albumMedias;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MediaBundle\Entity\Media", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\MediaBundle\Entity\TypeMedia", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeMedias;

    /**
     * @ORM\OneToMany(targetEntity="OSEL\InfoBundle\Entity\RedirectionMail", mappedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $redirectionMails;


    public function __construct()
    {
        $this->dateAdd = new \DateTime();
        $this->salt = md5(uniqid(null, true));
        if($this->username === null)
        {
            $this->username = strtolower($this->name . $this->lastname);
        }

        $this->logins = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->instruments = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set instrument
     *
     * @param string $instrument
     * @return User
     */
    public function setInstrument($instrument)
    {
        $this->instrument = $instrument;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return string 
     */
    public function getInstrument()
    {
        return $this->instrument;
    }


    /**
     * Set profession
     *
     * @param string $profession
     * @return User
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return User
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return User
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set postal
     *
     * @param string $postal
     * @return User
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return string 
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobilephone
     *
     * @param string $mobilephone
     * @return User
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    /**
     * Get mobilephone
     *
     * @return string 
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * Set emergencycontact
     *
     * @param string $emergencycontact
     * @return User
     */
    public function setEmergencycontact($emergencycontact)
    {
        $this->emergencycontact = $emergencycontact;

        return $this;
    }

    /**
     * Get emergencycontact
     *
     * @return string 
     */
    public function getEmergencycontact()
    {
        return $this->emergencycontact;
    }

    /**
     * Set emergencyrelation
     *
     * @param string $emergencyrelation
     * @return User
     */
    public function setEmergencyrelation($emergencyrelation)
    {
        $this->emergencyrelation = $emergencyrelation;

        return $this;
    }

    /**
     * Get emergencyrelation
     *
     * @return string 
     */
    public function getEmergencyrelation()
    {
        return $this->emergencyrelation;
    }

    /**
     * Set emergencyphone
     *
     * @param string $emergencyphone
     * @return User
     */
    public function setEmergencyphone($emergencyphone)
    {
        $this->emergencyphone = $emergencyphone;

        return $this;
    }

    /**
     * Get emergencyphone
     *
     * @return string 
     */
    public function getEmergencyphone()
    {
        return $this->emergencyphone;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return User
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
     * @return User
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
     * Set dateLastLogin
     *
     * @param \DateTime $dateLastLogin
     * @return User
     */
    public function setDateLastLogin($dateLastLogin)
    {
        $this->dateLastLogin = $dateLastLogin;

        return $this;
    }

    /**
     * Get dateLastLogin
     *
     * @return \DateTime 
     */
    public function getDateLastLogin()
    {
        return $this->dateLastLogin;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set emergencylastname
     *
     * @param string $emergencylastname
     * @return User
     */
    public function setEmergencylastname($emergencylastname)
    {
        $this->emergencylastname = $emergencylastname;

        return $this;
    }

    /**
     * Get emergencylastname
     *
     * @return string 
     */
    public function getEmergencylastname()
    {
        return $this->emergencylastname;
    }

    /**
     * Set emergencyname
     *
     * @param string $emergencyname
     * @return User
     */
    public function setEmergencyname($emergencyname)
    {
        $this->emergencyname = $emergencyname;

        return $this;
    }

    /**
     * Get emergencyname
     *
     * @return string 
     */
    public function getEmergencyname()
    {
        return $this->emergencyname;
    }

    /**
     * Add login
     *
     * @param \OSEL\UserBundle\Entity\login $logins
     * @return User
     */
    public function addLogin(\OSEL\UserBundle\Entity\login $logins)
    {
        $this->logins[] = $logins;

        $logins->setUser($this);

        return $this;
    }

    /**
     * Remove login
     *
     * @param \OSEL\UserBundle\Entity\login $logins
     */
    public function removeLogin(\OSEL\UserBundle\Entity\login $logins)
    {
        $this->logins->removeElement($logins);
    }

    /**
     * Get login
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogins()
    {
        return $this->logins;
    }

    /**
     * Add groupes
     *
     * @param \OSEL\UserBundle\Entity\Groupes $groupes
     * @return User
     */
    public function addGroupe(\OSEL\UserBundle\Entity\Groupes $groupes)
    {
        $this->groupes[] = $groupes;

        return $this;
    }

    /**
     * Remove groupes
     *
     * @param \OSEL\UserBundle\Entity\Groupes $groupes
     */
    public function removeGroupe(\OSEL\UserBundle\Entity\Groupes $groupes)
    {
        $this->groupes->removeElement($groupes);
    }

    /**
     * Get groupes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupes()
    {
        return $this->groupes;
    }

    /**
     * Add instruments
     *
     * @param \OSEL\UserBundle\Entity\Instruments $instruments
     * @return User
     */
    public function addInstrument(\OSEL\UserBundle\Entity\Instruments $instruments)
    {
        $this->instruments[] = $instruments;

        $instruments->setUser($this);

        return $this;
    }

    /**
     * Remove instruments
     *
     * @param \OSEL\UserBundle\Entity\Instruments $instruments
     */
    public function removeInstrument(\OSEL\UserBundle\Entity\Instruments $instruments)
    {
        $this->instruments->removeElement($instruments);
    }

    /**
     * Get instruments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInstruments()
    {
        return $this->instruments;
    }

    public function defineRest($pwd)
    {
        $this->username = $this->getName() . $this->getLastname();
        $this->password = $pwd;
    }

    public function eraseCredentials()
    {
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->enabled
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->enabled
        ) = unserialize($serialized);
    }

    public function getRoles()
    {
        $roles = array();
        foreach ($this->userRoles as $role) 
        {
            $roles[] = $role->getRole();
        }

        return $roles;
    }
    /**
     * Add userRoles
     *
     * @param \OSEL\UserBundle\Entity\Roles $userRoles
     * @return User
     */
    public function addUserRole(\OSEL\UserBundle\Entity\Roles $userRoles)
    {
        $this->userRoles[] = $userRoles;

        $userRoles->setUser($this);

        return $this;
    }

    /**
     * Remove userRoles
     *
     * @param \OSEL\UserBundle\Entity\Roles $userRoles
     */
    public function removeUserRole(\OSEL\UserBundle\Entity\Roles $userRoles)
    {
        $this->userRoles->removeElement($userRoles);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    public function getHeaders()
    {
        return array('id', 'Username', 'email', 'enabled', 'salt', 'password', 'Name', 'Lastname', 'Profession', 'Street', 'Number', 'Postal', 'City', 'Country', 'Phone', 'mobilephone', 'Birthday', 'date_add', 'date_update', 'newsletter');
    }

    public function toArray($id, $email, $instrument, $adress, $phone, $birthday, $emergency)
    {
        $tableau = array();
        
        if($id)
        {
            $tableau[] = $this->id;
        }
        
        $tableau[] = $this->lastname;
        $tableau[] = $this->name;
        
        if($email)
        {
            $tableau[] = $this->email;
        }

        if($instrument)
        {
            $instruments_export = array();
            foreach ($this->instruments as $instru) 
            {
                $instruments_export[] = $instru->getInstrument();
            }

            $instruments_implode = implode(', ', $instruments_export);
            $tableau[] = $instruments_implode;
        }
        
        if($phone)
        {
            $tableau[] = $this->mobilephone;
        }
        
        if($adress)            
        {
            $tableau[] = $this->street . ' ' .  $this->number . ', ' . $this->postal . ' ' . $this->city;
        }
        
        if($birthday)
        {
            $tableau[] = date_format($this->birthday, 'Y-m-d');
        }
        
        if($emergency)
        {
            $tableau[] = $this->emergencylastname . ' ' . $this->emergencyname . ': ' . $this->emergencyphone;
        }
        


        return $tableau;
    }

    /**
     * Add temp
     *
     * @param \OSEL\UserBundle\Entity\Temp $temp
     *
     * @return User
     */
    public function addTemp(\OSEL\UserBundle\Entity\Temp $temp)
    {
        $this->temps[] = $temp;

        return $this;
    }

    /**
     * Remove temp
     *
     * @param \OSEL\UserBundle\Entity\Temp $temp
     */
    public function removeTemp(\OSEL\UserBundle\Entity\Temp $temp)
    {
        $this->temps->removeElement($temp);
    }

    /**
     * Get temps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTemps()
    {
        return $this->temps;
    }

    /**
     * Add event
     *
     * @param \OSEL\EventBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(\OSEL\EventBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \OSEL\EventBundle\Entity\Event $event
     */
    public function removeEvent(\OSEL\EventBundle\Entity\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add ridingGroup
     *
     * @param \OSEL\EventBundle\Entity\RidingGroup $ridingGroup
     *
     * @return User
     */
    public function addRidingGroup(\OSEL\EventBundle\Entity\RidingGroup $ridingGroup)
    {
        $this->ridingGroups[] = $ridingGroup;

        return $this;
    }

    /**
     * Remove ridingGroup
     *
     * @param \OSEL\EventBundle\Entity\RidingGroup $ridingGroup
     */
    public function removeRidingGroup(\OSEL\EventBundle\Entity\RidingGroup $ridingGroup)
    {
        $this->ridingGroups->removeElement($ridingGroup);
    }

    /**
     * Get ridingGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRidingGroups()
    {
        return $this->ridingGroups;
    }

    /**
     * Add ridingGroupMembre
     *
     * @param \OSEL\EventBundle\Entity\RidingGroupMembre $ridingGroupMembre
     *
     * @return User
     */
    public function addRidingGroupMembre(\OSEL\EventBundle\Entity\RidingGroupMembre $ridingGroupMembre)
    {
        $this->ridingGroupMembres[] = $ridingGroupMembre;

        return $this;
    }

    /**
     * Remove ridingGroupMembre
     *
     * @param \OSEL\EventBundle\Entity\RidingGroupMembre $ridingGroupMembre
     */
    public function removeRidingGroupMembre(\OSEL\EventBundle\Entity\RidingGroupMembre $ridingGroupMembre)
    {
        $this->ridingGroupMembres->removeElement($ridingGroupMembre);
    }

    /**
     * Get ridingGroupMembres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRidingGroupMembres()
    {
        return $this->ridingGroupMembres;
    }

    /**
     * Add subEvent
     *
     * @param \OSEL\EventBundle\Entity\SubEvents $subEvent
     *
     * @return User
     */
    public function addSubEvent(\OSEL\EventBundle\Entity\SubEvents $subEvent)
    {
        $this->subEvents[] = $subEvent;

        return $this;
    }

    /**
     * Remove subEvent
     *
     * @param \OSEL\EventBundle\Entity\SubEvents $subEvent
     */
    public function removeSubEvent(\OSEL\EventBundle\Entity\SubEvents $subEvent)
    {
        $this->subEvents->removeElement($subEvent);
    }

    /**
     * Get subEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubEvents()
    {
        return $this->subEvents;
    }

    /**
     * Add subscribeEvent
     *
     * @param \OSEL\EventBundle\Entity\SubscribeEvent $subscribeEvent
     *
     * @return User
     */
    public function addSubscribeEvent(\OSEL\EventBundle\Entity\SubscribeEvent $subscribeEvent)
    {
        $this->subscribeEvents[] = $subscribeEvent;

        return $this;
    }

    /**
     * Remove subscribeEvent
     *
     * @param \OSEL\EventBundle\Entity\SubscribeEvent $subscribeEvent
     */
    public function removeSubscribeEvent(\OSEL\EventBundle\Entity\SubscribeEvent $subscribeEvent)
    {
        $this->subscribeEvents->removeElement($subscribeEvent);
    }

    /**
     * Get subscribeEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscribeEvents()
    {
        return $this->subscribeEvents;
    }

    /**
     * Add yearMedia
     *
     * @param \OSEL\MediaBundle\Entity\YearMedia $yearMedia
     *
     * @return User
     */
    public function addYearMedia(\OSEL\MediaBundle\Entity\YearMedia $yearMedia)
    {
        $this->yearMedias[] = $yearMedia;

        return $this;
    }

    /**
     * Remove yearMedia
     *
     * @param \OSEL\MediaBundle\Entity\YearMedia $yearMedia
     */
    public function removeYearMedia(\OSEL\MediaBundle\Entity\YearMedia $yearMedia)
    {
        $this->yearMedias->removeElement($yearMedia);
    }

    /**
     * Get yearMedias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getYearMedias()
    {
        return $this->yearMedias;
    }

    /**
     * Add albumMedia
     *
     * @param \OSEL\MediaBundle\Entity\Album $albumMedia
     *
     * @return User
     */
    public function addAlbumMedia(\OSEL\MediaBundle\Entity\Album $albumMedia)
    {
        $this->albumMedias[] = $albumMedia;

        return $this;
    }

    /**
     * Remove albumMedia
     *
     * @param \OSEL\MediaBundle\Entity\Album $albumMedia
     */
    public function removeAlbumMedia(\OSEL\MediaBundle\Entity\Album $albumMedia)
    {
        $this->albumMedias->removeElement($albumMedia);
    }

    /**
     * Get albumMedias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlbumMedias()
    {
        return $this->albumMedias;
    }

    /**
     * Add media
     *
     * @param \OSEL\MediaBundle\Entity\Media $media
     *
     * @return User
     */
    public function addMedia(\OSEL\MediaBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \OSEL\MediaBundle\Entity\Media $media
     */
    public function removeMedia(\OSEL\MediaBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add typeMedia
     *
     * @param \OSEL\MediaBundle\Entity\TypeMedia $typeMedia
     *
     * @return User
     */
    public function addTypeMedia(\OSEL\MediaBundle\Entity\TypeMedia $typeMedia)
    {
        $this->typeMedias[] = $typeMedia;

        return $this;
    }

    /**
     * Remove typeMedia
     *
     * @param \OSEL\MediaBundle\Entity\TypeMedia $typeMedia
     */
    public function removeTypeMedia(\OSEL\MediaBundle\Entity\TypeMedia $typeMedia)
    {
        $this->typeMedias->removeElement($typeMedia);
    }

    /**
     * Get typeMedias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeMedias()
    {
        return $this->typeMedias;
    }

    /**
     * Add redirectionMail
     *
     * @param \OSEL\InfoBundle\Entity\RedirectionMail $redirectionMail
     *
     * @return User
     */
    public function addRedirectionMail(\OSEL\InfoBundle\Entity\RedirectionMail $redirectionMail)
    {
        $this->redirectionMails[] = $redirectionMail;

        return $this;
    }

    /**
     * Remove redirectionMail
     *
     * @param \OSEL\InfoBundle\Entity\RedirectionMail $redirectionMail
     */
    public function removeRedirectionMail(\OSEL\InfoBundle\Entity\RedirectionMail $redirectionMail)
    {
        $this->redirectionMails->removeElement($redirectionMail);
    }

    /**
     * Get redirectionMails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRedirectionMails()
    {
        return $this->redirectionMails;
    }

    /**
     * Add news
     *
     * @param \OSEL\NewsBundle\Entity\News $news
     *
     * @return User
     */
    public function addNews(\OSEL\NewsBundle\Entity\News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param \OSEL\NewsBundle\Entity\News $news
     */
    public function removeNews(\OSEL\NewsBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add imageNews
     *
     * @param \OSEL\NewsBundle\Entity\ImageNews $imageNews
     *
     * @return User
     */
    public function addImageNews(\OSEL\NewsBundle\Entity\ImageNews $imageNews)
    {
        $this->imageNews[] = $imageNews;

        return $this;
    }

    /**
     * Remove imageNews
     *
     * @param \OSEL\NewsBundle\Entity\ImageNews $imageNews
     */
    public function removeImageNews(\OSEL\NewsBundle\Entity\ImageNews $imageNews)
    {
        $this->imageNews->removeElement($imageNews);
    }

    /**
     * Get imageNews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImageNews()
    {
        return $this->imageNews;
    }

    /**
     * Add score
     *
     * @param \OSEL\ScoreheetBundle\Entity\Score $score
     *
     * @return User
     */
    public function addScore(\OSEL\ScoreheetBundle\Entity\Score $score)
    {
        $this->scores[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param \OSEL\ScoreheetBundle\Entity\Score $score
     */
    public function removeScore(\OSEL\ScoreheetBundle\Entity\Score $score)
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
     * Add scoresModified
     *
     * @param \OSEL\ScoreBundle\Entity\Score $scoresModified
     *
     * @return User
     */
    public function addScoresModified(\OSEL\ScoreBundle\Entity\Score $scoresModified)
    {
        $this->scores_modified[] = $scoresModified;

        return $this;
    }

    /**
     * Remove scoresModified
     *
     * @param \OSEL\ScoreBundle\Entity\Score $scoresModified
     */
    public function removeScoresModified(\OSEL\ScoreBundle\Entity\Score $scoresModified)
    {
        $this->scores_modified->removeElement($scoresModified);
    }

    /**
     * Get scoresModified
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScoresModified()
    {
        return $this->scores_modified;
    }
}
