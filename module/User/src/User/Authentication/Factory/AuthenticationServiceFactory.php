<?php

namespace User\Authentication\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use User\Authentication\UserAuthenticationAdapter;
use User\Authentication\UserAuthenticationStorage;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class AuthenticationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        
        $adapter = new UserAuthenticationAdapter($userMapper);
        $storage = new UserAuthenticationStorage($userMapper);

        return new AuthenticationService($storage, $adapter);
    }

}
