<?php

namespace User\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Service\UserPasswordService;

/**
 * The factory creates an instance of a UserPasswordServiceInterface implementation.
 */
class UserPasswordServiceFactory implements FactoryInterface
{

    /**
     * Creates an instance of a class that implements the UserPasswordServiceInterface interface.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \User\Service\UserPasswordServiceInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        return new UserPasswordService($userMapper);
    }

}
