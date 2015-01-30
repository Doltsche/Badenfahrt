<?php

namespace User\Authentication\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use User\Authentication\UserAuthenticationAdapter;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class AuthenticationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authenticationService = new AuthenticationService();
        
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        $authenticationService->setAdapter(new UserAuthenticationAdapter($userMapper));
        
        return $authenticationService;
    }

}
