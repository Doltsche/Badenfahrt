<?php
 
namespace Badenfahrt\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Entity\User as ZfcUserEntity;

/**
 *
 *
 * @author Samuel Egger
 */
class User extends ZfcUserEntity implements ProviderInterface
{	
	protected $firstName;
	
	protected $lastName;
	
	protected $streetAndNumber;
	
	protected $postalCode;
	
	protected $city;
	
	protected $phone;

    protected $roles;

    /**
     * Initializes the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
