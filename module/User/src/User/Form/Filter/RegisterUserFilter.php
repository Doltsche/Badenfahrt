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
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
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
    }
}