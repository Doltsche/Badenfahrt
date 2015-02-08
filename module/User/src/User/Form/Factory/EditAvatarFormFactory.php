<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\EditAvatarForm;

/**
 * The factory creates an instance of the EditAvatarForm class.
 */
class EditAvatarFormFactory implements FactoryInterface
{

    /**
     * Creates an instance of the EditAvatarForm class.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return EditAvatarForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new EditAvatarForm();
        $form->setAttribute('id', 'editAvatarForm');
        
        return $form;
    }

}