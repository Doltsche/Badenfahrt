<?php

namespace User\Mapper;

/**
 * Mapper interface to access Role objects.
 */
interface RoleMapperInterface
{

    /**
     * Finds a Role object by the ginve roleId.
     * 
     * @param string $roleId
     * @return \User\Model\Role
     */
    public function findByRoleId($roleId);
}
