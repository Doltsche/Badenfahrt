<?php

namespace User\Mapper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of UserMapper
 *
 * @author Dev
 */
class DoctrineRoleMapper implements RoleMapperInterface
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByRoleId($roleId)
    {
        $roleRepository = $this->entityManager->getRepository('User\Model\Role');
        $role = $roleRepository->findOneBy(array('roleId' => $roleId));

        return $role;
    }

}
