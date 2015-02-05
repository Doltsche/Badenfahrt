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

    public function __construct($userMapper, $userMailService) {
        $this->userMapper = $userMapper;
        $this->userMailService = $userMailService;
    }

    public function isSatisfied($user, $plainPassword) {
        //encrypt pwd
        $encryptedPassword = mcrypt_create_iv(128, $plainPassword);

        return $user->getPassword() == $encryptedPassword;
    }

    public function updatePassword($user, $plainPassword) {

        $encryptedPassword = mcrypt_create_iv(128, $plainPassword);
        $user->setPassword($encryptedPassword);

        return $user;
    }

}
