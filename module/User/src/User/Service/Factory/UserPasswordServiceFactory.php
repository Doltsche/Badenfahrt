<?php

namespace User\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Service\UserPasswordService;

/**
 * Description of UserPasswordServiceFactory
 *
 * @author avogel
 */
class UserPasswordServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        $userMailService = $serviceLocator->get('User\Service\UserMailServiceInterface');
        return new UserPasswordService($userMapper, $userMailService);
    }
}
