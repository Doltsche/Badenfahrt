<?php

namespace User\Form;

use Zend\Form\Form;

/**
 * Description of PostForm
 *
 * @author Dev
 */
class EditAvatarForm extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'file',
            'name' => 'avatar',
            'attributes' => array(
                'value' => 'Avatar hochladen'
            )
        ));

        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Bild hochladen'
            )
        ));
    }
}
