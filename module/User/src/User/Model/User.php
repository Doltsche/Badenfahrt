<?php

namespace User\Model;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The User entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @author Samuel Egger
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
     * @ORM\OneToOne(targetEntity="User\Model\Personal")
     * @ORM\JoinColumn(name="personal_id", referencedColumnName="id")
     * */
    protected $personal;

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
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->id = 0;
        $this->state = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function getPersonal()
    {
        return $this->personal;
    }

    public function setPersonal($personal)
    {
        $this->personal = $personal;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

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

}
