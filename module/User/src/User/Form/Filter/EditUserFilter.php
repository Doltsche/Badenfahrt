<?php

namespace User\Form\Filter;

use Zend\InputFilter\InputFilter;
use User\Form\Validator\PasswordVerifyValidator;
use User\Form\Validator\IdentityUniqueValidator;
use User\Form\Validator\PasswordValidator;
use User\Form\Validator\DisplayNameUniqueValidator;

/**
 * The filter class for the edit user form.
 */
class EditUserFilter extends InputFilter
{

    /**
     * Creates a new instance of the EditUserFilter class.
     * 
     * @param \User\Mapper\UserMapperInterface $userMapper
     * @param \Zend\Authentication\AuthenticationService $authenticationService
     */
    public function __construct($userMapper, $authenticationService)
    {
        $this->add(array(
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
                ),
                new DisplayNameUniqueValidator($userMapper, $authenticationService),
            ),
        ));

        $this->add(array(
            'name' => 'identity',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                ),
                new IdentityUniqueValidator($userMapper, $authenticationService),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'required' => false,
            'allowEmpty' => false,
            'validators' => array(
                new PasswordValidator(),
            )
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'required' => false,
            'allowEmpty' => false,
            'validators' => array(
                new PasswordVerifyValidator(),
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
