<?php

namespace User\Form;

use User\Form\PersonalFormBase;

/**
 * Description of PostForm
 *
 * @author Dev
 */
class RegisterPersonalForm extends PersonalFormBase
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Speichern'
            )
        ));
    }

}
