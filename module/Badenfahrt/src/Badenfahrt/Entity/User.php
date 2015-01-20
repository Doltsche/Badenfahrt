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
	protected $token;

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
	
	public function getToken(){
		return $this->token;
	}
	
	public function setToken($token){
		$this->token = $token;
	}
	
	public function getFirstName(){
		return $this->firstName;
	}
	
	public function setFirstName($firstName){
		$this->firstName = $firstName;
	}
	
	public function getLastName(){
		return $this->lastName;
	}
	
	public function setLastName($lastName){
		$this->lastName = $lastName;
	}
	
	public function getStreetAndNumber(){
		return $this->streetAndNumber;
	}
	
	public function setStreetAndNumber($streetAndNumber){
		$this->streetAndNumber = $streetAndNumber;
	}
	
	public function getPostalCode(){
		return $this->postalCode;
	}
	
	public function setPostalCode($postalCode){
		$this->postalCode = $postalCode;
	}
	
	public function getCity(){
		return $this->city;
	}
	
	public function setCity($city){
		$this->city = $city;
	}
	
	public function getPhone(){
		return $this->phone;
	}
	
	public function setPhone($phone){
		$this->phone = $phone;
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
