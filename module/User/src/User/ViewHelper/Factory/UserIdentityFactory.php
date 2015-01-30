<?php

namespace User\ViewHelper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\ViewHelper\UserIdentity;

/**
 * Description of LoginViewHelperFactory
 *
 * @author Dev
 */
class UserIdentityFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();
        $authenticationService = $serviceManager->get('user_authentication_service');
        
        return new UserIdentity($authenticationService);
    }
}
