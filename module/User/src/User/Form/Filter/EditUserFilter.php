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
class EditUserFilter extends InputFilter
{

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
                new IdentityUniqueValidator($userMapper),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'required' => false,
            'allowEmpty' => true,
            'filters' => array(array('name' => 'StringTrim')),
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
                new PasswordVerifyValidator(),
            ),
        ));
    }
}
