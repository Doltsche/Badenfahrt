<?php

namespace User\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Service\UserMailService;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class UserMailServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('Config')['mail']['transport'];

        return new UserMailService($options);
    }

}
