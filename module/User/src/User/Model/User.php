<?php

namespace User\Model;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 * @author Samuel Egger
 */
class User implements ProviderInterface, InputFilterAwareInterface
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
    protected $passwordVerify;

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

    public function getPasswordVerify()
    {
        return $this->passwordVerify;
    }

    public function setPasswordVerify($passwordVerify)
    {
        $this->passwordVerify = $passwordVerify;
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

    public function getInputFilter()
    {
        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'displayName',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                )
            ),
        ));

        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'passwordVerify',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'identity',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'firstname',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'lastname',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'streetAndNr',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'postalCode',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(array('name' => 'Digits')),
            'validators' => array(
                array(
                    'name' => 'Digits',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 10,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'city',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'phoneNumber',
            'required' => false,
            'allowEmpty' => true,
            'filters' => array(array('name' => 'Digits')),
            'validators' => array(
                array(
                    'name' => 'Digits',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 7,
                        'max' => 13,
                    ),
                )
            ),
        ));

        return $inputFilter;
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
//        // TODO
//        
//        $guestRole = new Role();
//        $guestRole->setId(1);
//        $guestRole->setRoleId('guest');
//        
//        $userRole = new Role();
//        $userRole->setId(2);
//        $userRole->getParent($guestRole);
//        $userRole->setRoleId('user');
//        
//        return array($userRole);

        $values = $this->roles->getValues();
        return $values;
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
