<?php

namespace User\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider;

/**
 * Description of DoctrineORMUserMapperFactory
 *
 * @author Dev
 */
class IdentityProviderFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authenticationService = $serviceLocator->get('user_authentication_service');
        $simpleIdentityProvider = new AuthenticationIdentityProvider($authenticationService);
        
        $config = $serviceLocator->get('BjyAuthorize\Config');
        $simpleIdentityProvider->setDefaultRole($config['default_role']);
        $simpleIdentityProvider->setAuthenticatedRole($config['authenticated_role']);
        
        return $simpleIdentityProvider;
    }

}
