<?php

namespace User\Service;

/**
 *
 * @author Dev
 */
interface MailServiceInterface
{
    public function sendConfirmationRequest($user);
    
    public function sendResetPassword($user);
}
