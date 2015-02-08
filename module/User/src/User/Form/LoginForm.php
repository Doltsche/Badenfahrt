<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * The login form.
 */
class LoginForm extends Form
{

    /**
     * Creates a new instance of the LoginForm class.
     * 
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $inputFilter = new InputFilter();

        $this->add(array(
            'type' => 'text',
            'name' => 'identity',
            'options' => array(
                'label' => 'Identität',
            ),
            'attributes' => array(
                'placeholder' => 'Email',
            ),
        ));

        $inputFilter->add(array(
            'name' => 'identity',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte Identität eingeben.'
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 'Die Identität muss mindestens 3 Zeichen lang sein.',
                            \Zend\Validator\StringLength::TOO_SHORT => 'Die Identität darf maximal 255 Zeichen lang sein.',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'type' => 'password',
            'name' => 'password',
            'options' => array(
                'label' => 'Passwort',
            ),
            'attributes' => array(
                'placeholder' => 'Passwort',
            ),
        ));

        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Bitte Passwort eingeben.'
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 'Das Passwort muss mindestens 3 Zeichen lang sein.',
                            \Zend\Validator\StringLength::TOO_SHORT => 'Das Passwort darf maximal 255 Zeichen lang sein.',
                        ),
                    ),
                ),
            ),
        ));

        $this->setInputFilter($inputFilter);

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Login'
            )
        ));
    }

}
