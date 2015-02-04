<?php

namespace User\Form;

use User\Form\UserFormBase;

/**
 * Description of PostForm
 *
 * @author Dev
 */
class EditUserForm extends UserFormBase
{

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
