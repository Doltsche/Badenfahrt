<?php

namespace User\Form;

use User\Model\User;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class UserFormBase extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new User());
        
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
    }
}