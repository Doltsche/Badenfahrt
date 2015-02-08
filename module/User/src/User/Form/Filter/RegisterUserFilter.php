<?php

namespace User\Form\Filter;

use Zend\InputFilter\InputFilter;
use User\Form\Validator\PasswordVerifyValidator;
use User\Form\Validator\IdentityUniqueValidator;
use User\Form\Validator\DisplayNameUniqueValidator;

/**
 * Description of RegisterFilter
 *
 * @author Dev
 */
class RegisterUserFilter extends InputFilter
{

    protected $userMapper;

    public function __construct($userMapper)
    {   
        $this->userMapper = $userMapper;

        $this->add(array(
            'name' => 'displayName',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'locale' => 'de',
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
                new DisplayNameUniqueValidator($this->userMapper),
            ),
        ));

        $this->add(array(
            'name' => 'identity',
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                ),
                new IdentityUniqueValidator($this->userMapper),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'required' => true,
            'allowEmpty' => false,
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

        $this->add(array(
            'name' => 'passwordVerify',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
                new PasswordVerifyValidator()
            ),
        ));

        $this->add(array(
            'name' => 'firstname',
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
            'name' => 'lastname',
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
            'name' => 'streetAndNr',
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
            'name' => 'postalCode',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
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
            'name' => 'phone',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 7,
                        'max' => 20,
                    ),
                )
            ),
        ));
    }

}
