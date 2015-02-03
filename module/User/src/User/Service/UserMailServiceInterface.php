<?php

namespace User\Service;

/**
 *
 * @author Dev
 */
interface UserMailServiceInterface
{
    public function sendConfirmationRequest($user);
    
    public function sendResetPassword($user);
}
