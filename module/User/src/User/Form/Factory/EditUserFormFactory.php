<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\EditUserForm;
use User\Form\Filter\EditUserFilter;

/**
 * The factory creates an instance of the EditUserForm class.
 */
class EditUserFormFactory implements FactoryInterface
{

    /**
     * Creates an instance of the EditUserForm class.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return EditUserForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        $authenticationService = $serviceLocator->get('user_authentication_service');
        
        $filter = new EditUserFilter($userMapper, $authenticationService);

        $form = new EditUserForm();
        $form->setInputFilter($filter);

        $form->setAttribute('id', 'editUserForm');
        
        return $form;
    }

}