<?php

namespace User\Mapper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * The class implements the RoleMapperInterface interface using doctrine orm.
 */
class DoctrineRoleMapper implements RoleMapperInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Creates a new instance of the DoctrineRoleMapper class.
     * 
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Finds a Role object by the ginve roleId.
     * 
     * @param string $roleId
     * @return \User\Model\Role
     */
    public function findByRoleId($roleId)
    {
        $roleRepository = $this->entityManager->getRepository('User\Model\Role');
        $role = $roleRepository->findOneBy(array('roleId' => $roleId));

        return $role;
    }

}
