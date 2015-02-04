<?php

namespace User\Form;

use User\Model\Personal;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class PersonalFormBase extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Personal());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
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
