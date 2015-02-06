<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\EditAvatarForm;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class EditAvatarFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        
        $form = new EditAvatarForm();
        $form->setAttribute('id', 'editAvatarForm');
        
        return $form;
    }

}