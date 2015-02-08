<?php

namespace User\Service;

/**
 * Implements the UserPasswordServiceInterface interface.
 */
class UserPasswordService implements UserPasswordServiceInterface
{

    /**
     * @var \User\Mapper\UserMapperInterface 
     */
    protected $userMapper;

    /**
     * Creates a new instance of the UserPasswordService class.
     * 
     * @param User\Mapper\UserMapperInterface $userMapper
     */
    public function __construct($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * Encrypts the given password and sets it on the user.
     * 
     * @param \User\Model\User $user
     * @param string $plainPassword
     */
    public function updatePassword($user, $plainPassword)
    {

        $encryptedPassword = md5($plainPassword);
        $user->setPassword($encryptedPassword);

        return $user;
    }

    /**
     * Checks if the given plain password matches the encrypted password
     * of the user.
     * 
     * @param \User\Model\User $user
     * @param string $plainPassword
     */
    public function isSatisfied($user, $plainPassword)
    {
        // Encrypt pwd
        $encryptedPassword = md5($plainPassword);

        return $user->getPassword() == $encryptedPassword;
    }

}
