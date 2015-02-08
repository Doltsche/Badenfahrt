<?php

namespace User\Mapper;

/**
 * Mapper interface to access User objects.
 */
interface UserMapperInterface
{

    /**
     * Finds a User object by the given id.
     * 
     * @param int $id
     * @return \User\Model\User
     */
    public function findById($id);

    /**
     * Finds a User object by the given identity.
     * 
     * @param string $identity
     * @return \User\Model\User
     */
    public function findByIdentity($identity);

    /**
     * Finds a User object by the givne display name.
     * 
     * @param string $displayName
     * @return \User\Model\User
     */
    public function findByDisplayName($displayName);

    /**
     * Finds a User object by the given token.
     * 
     * @param string $token
     * @return \User\Mapper\User
     */
    public function findByToken($token);

    /**
     * Finds all User objects.
     * 
     * @return array
     */
    public function findAll();

    /**
     * Saves the given User object.
     * 
     * @param \User\Model\User $user
     */
    public function save($user);

    /**
     * Removes the given user object.
     * 
     * @param \User\Model\User $user
     */
    public function remove($user);
}
