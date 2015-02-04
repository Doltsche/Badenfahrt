<?php

namespace User\Form\Filter;

use Zend\InputFilter\InputFilter;

/**
 * Description of RegisterFilter
 *
 * @author Dev
 */
class RegisterPersonalFilter extends InputFilter
{

    public function __construct()
    {
        $this->add(array(
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

        $this->add(array(
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

        $this->add(array(
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

        $this->add(array(
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

        $this->add(array(
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

        $this->add(array(
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
    }

}
