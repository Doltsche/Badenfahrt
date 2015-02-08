<?php

namespace User\Mapper\Factory;

use User\Mapper\DoctrineUserMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * The factory creates an instance of a UserMapperInterface implementation.
 */
class UserMapperFactory implements FactoryInterface
{

    /**
     * Creates an instance of a class that implements the UserMapperInterface interface.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserMapperInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return new DoctrineUserMapper($entityManager);
    }

}
