<?php

namespace User\Form;

use User\Form\UserFormBase;

/**
 * The edit user form.
 */
class EditUserForm extends UserFormBase
{

    /**
     * Creates a new instance of the EditUserForm class.
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
                'value' => 'Änderungen speichern'
            )
        ));
    }

}
