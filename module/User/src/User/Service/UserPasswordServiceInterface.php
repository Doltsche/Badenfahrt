<?php

namespace User\Service;

/**
 * The UserPasswordServiceInterface interface defines methods related 
 * to the user password.
 */
interface UserPasswordServiceInterface 
{
    /**
     * Encrypts the given password and sets it on the user.
     * 
     * @param \User\Model\User $user
     * @param string $plainPassword
     */
    public function updatePassword($user, $plainPassword);
    
    /**
     * Checks if the given plain password matches the encrypted password
     * of the user.
     * 
     * @param \User\Model\User $user
     * @param string $password
     */
    public function isSatisfied($user, $password);
}
