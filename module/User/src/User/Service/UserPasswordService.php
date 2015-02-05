<?php

namespace User\Service;

/**
 * Description of UserPasswordService
 *
 * @author avogel
 */
class UserPasswordService implements UserPasswordServiceInterface {
    
    protected $userMapper;
    protected $userMailService;
    
    public function __construct($userMapper, $userMailService) 
    {
        $this->userMapper = $userMapper;
        $this->userMailService = $userMailService;
    }
    
    public function isSatisfied($user, $plainPassword)
    {
        //encrypt pwd
        $user->getPassword();
    }

    public function updatePassword($user, $plainPassword) {
        

        // TODO: Encrypt plainPassword;
        $encryptedPassword = '';
        $user->setPasswor($encryptedPassword);
        
        return $user;
    }
}
