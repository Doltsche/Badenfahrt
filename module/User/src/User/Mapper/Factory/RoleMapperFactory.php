<?php

namespace User\Mapper\Factory;

use User\Mapper\DoctrineRoleMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * The factory creates an instance of a RoleMapperInterface implementation.
 */
class RoleMapperFactory implements FactoryInterface
{

    /**
     * Creates an instance of a class that implements the RoleMapperInterface interface.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return RoleMapperInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return new DoctrineRoleMapper($entityManager);
    }

}
