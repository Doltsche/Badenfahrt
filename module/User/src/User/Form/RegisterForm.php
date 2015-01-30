<?php

namespace User\Form;

use Zend\Form\Form;

/**
 * Description of PostForm
 *
 * @author Dev
 */
class RegisterForm extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->add(array(
            'name' => 'user-fieldset',
            'type' => 'User\Form\UserFieldset',
            'options' => array(
                'use_as_base_fieldset' => true,
            )
        ));

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Registrieren'
            )
        ));
    }

}
