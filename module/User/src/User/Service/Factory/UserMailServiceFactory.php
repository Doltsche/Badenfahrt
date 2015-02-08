<?php

namespace User\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Service\UserMailService;

/**
 * The factory creates an instance of a UserMailServiceInterface implementation.
 */
class UserMailServiceFactory implements FactoryInterface
{

    /**
     * Creates an instance of a class that implements the UserMailServiceInterface interface.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \User\Service\UserMailServiceInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('Config')['mail']['transport'];

        return new UserMailService($options);
    }

}
