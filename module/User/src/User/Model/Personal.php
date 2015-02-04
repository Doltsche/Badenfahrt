<?php

namespace User\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * The Personal entity.
 * 
 * @ORM\Entity
 * @ORM\Table(name="personal")
 *
 * @author Samuel Egger
 */
class Personal
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
     * @ORM\Column(type="string", length=10)
     */
    protected $phone;

    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->id = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getStreetAndNr()
    {
        return $this->streetAndNr;
    }

    public function setStreetAndNr($streetAndNr)
    {
        $this->streetAndNr = $streetAndNr;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

}
