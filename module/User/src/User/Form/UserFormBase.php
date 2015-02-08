<?php

namespace User\Form;

use User\Model\User;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Abstract base class for the edit user and register user form class.
 */
abstract class UserFormBase extends Form
{
    /**
     * Initializes a new instance of the abstract UserFormBase class.
     * 
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new User());
        
        $element = new \Zend\Form\Element\Text();
        
        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));
        
        $this->add(array(
            'type' => 'text',
            'name' => 'displayName',
            'options' => array(
                'label' => 'Anzeige Name',
            ),
        ));
        
        $this->add(array(
            'type' => 'text',
            'name' => 'identity',
            'options' => array(
                'label' => 'E-Mail',
            ),
        ));
        
        $this->add(array(
            'type' => 'password',
            'name' => 'password',
            'options' => array(
                'label' => 'Passwort',
            ),
        ));
        
        $this->add(array(
            'type' => 'password',
            'name' => 'passwordVerify',
            'options' => array(
                'label' => 'Passwort bestätigen',
            ),
        ));
        
        $this->add(array(
            'type' => 'text',
            'name' => 'firstname',
            'options' => array(
                'label' => 'Vorname',
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'lastname',
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'streetAndNr',
            'options' => array(
                'label' => 'Strasse und Nummer',
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'postalCode',
            'options' => array(
                'label' => 'Postleitzahl',
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'city',
            'options' => array(
                'label' => 'Stadt',
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'phone',
            'options' => array(
                'label' => 'Telefon',
            ),
        ));
    }
}