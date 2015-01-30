<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Description of PostForm
 *
 * @author Dev
 */
class LoginForm extends Form
{

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
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter User Name!'
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 'Please enter User Name between 4 to 20 character!',
                            \Zend\Validator\StringLength::TOO_SHORT => 'Please enter User Name between 4 to 20 character!'
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
