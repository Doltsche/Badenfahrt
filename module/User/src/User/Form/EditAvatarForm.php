<?php

namespace User\Form;

use Zend\Form\Form;

/**
 * The edit avatar form.
 */
class EditAvatarForm extends Form
{

    /**
     * Creates a new instance of the EditAvatarForm class.
     * 
     * @param string $name
     * @param array $options
     */
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
