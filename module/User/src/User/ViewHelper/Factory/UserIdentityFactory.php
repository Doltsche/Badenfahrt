<?php

namespace User\ViewHelper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\ViewHelper\UserIdentity;

/**
 * The factory creates an instance of the UserIdentity class.
 */
class UserIdentityFactory implements FactoryInterface
{

    /**
     * Creates an instance of the UserIdentity class.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserIdentity
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();
        $authenticationService = $serviceManager->get('user_authentication_service');

        return new UserIdentity($authenticationService);
    }

}
