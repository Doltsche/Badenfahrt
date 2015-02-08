<?php

namespace User\Service;

/**
 * The UserMailServiceInterface interface defines methods to send the user
 * specific emails.
 */
interface UserMailServiceInterface
{
    /**
     * Sends the given user a confirmation request email.
     * 
     * @param \User\Model\User $user
     */
    public function sendConfirmationRequest($user);
    
    /**
     * Sends the given user a password reset mail.
     * 
     * @param \User\Model\User $user $user
     */
    public function sendResetPassword($user);
}
