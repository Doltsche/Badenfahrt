<?php

namespace User\Model;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The User entity class.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements ProviderInterface
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true,  length=255)
     */
    protected $identity;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $displayName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatar;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $token;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="User\Model\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $streetAndNr;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=4)
     */
    protected $postalCode;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    protected $phone;

    /**
     * Creates a new instance of the User class.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->id = 0;
        $this->state = 0;
    }

    /**
     * Get the id.
     * 
     * @return int.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id.
     * 
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the encrypted password.
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the encrypted password.
     * 
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get the identity.
     * 
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Set the identity.
     * 
     * @param string $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * Get the display name.
     * 
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set the display name.
     * 
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get the name of the avatar picture.
     * 
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set the name of the avatar picture.
     * 
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get the user state.
     * 
     * 0 = not confirmed account
     * 1 = confirmed account
     * 
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the user state.
     * 
     * 0 = not confirmed account
     * 1 = confirmed account
     * 
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get the user token.
     * 
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the user token.
     * 
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get the user roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Set the user roles.
     * 
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    /**
     * Get the first name.
     * 
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the first name.
     * 
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the last name.
     * 
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the last name.
     * 
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the street and number.
     * 
     * @return string
     */
    public function getStreetAndNr()
    {
        return $this->streetAndNr;
    }

    /**
     * Set the street and number.
     * 
     * @param string $streetAndNr
     */
    public function setStreetAndNr($streetAndNr)
    {
        $this->streetAndNr = $streetAndNr;
    }

    /**
     * Get the postal code.
     * 
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set the postal code.
     * 
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * Get the city.
     * 
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the city.
     * 
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get the phone number.
     * 
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the phone number.
     * 
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

}
