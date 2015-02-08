<?php

namespace User\Form;

use User\Form\UserFormBase;

/**
 * The register user form.
 */
class RegisterUserForm extends UserFormBase
{

    /**
     * Creates a new instance of the RegisterUserForm class.
     * 
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Registrieren'
            )
        ));
    }

}
