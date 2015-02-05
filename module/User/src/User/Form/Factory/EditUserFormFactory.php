<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\EditUserForm;
use User\Form\Filter\EditUserFilter;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class EditUserFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        $authenticationService = $serviceLocator->get('user_authentication_service');
        
        $filter = new EditUserFilter($userMapper, $authenticationService);

        $form = new EditUserForm();
        $form->setInputFilter($filter);
        
        $user = $authenticationService->getIdentity();
        $form->bind($user);

        $form->setAttribute('id', 'editUserForm');
        
        return $form;
    }

}