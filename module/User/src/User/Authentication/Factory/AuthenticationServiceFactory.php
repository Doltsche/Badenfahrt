<?php

namespace User\Authentication\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use User\Authentication\UserAuthenticationAdapter;
use User\Authentication\UserAuthenticationStorage;

/**
 * The factory creates an instance of the AuthenticationService class.
 */
class AuthenticationServiceFactory implements FactoryInterface
{

    /**
     * Creates an instance of the RegisterUserForm class.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        $passwordService = $serviceLocator->get('User\Service\UserPasswordServiceInterface');
        
        $adapter = new UserAuthenticationAdapter($userMapper, $passwordService);
        $storage = new UserAuthenticationStorage($userMapper);

        return new AuthenticationService($storage, $adapter);
    }

}
