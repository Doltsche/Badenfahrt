<?php

namespace User\Service;

/**
 * Implements the UserPasswordServiceInterface interface.
 */
class UserPasswordService implements UserPasswordServiceInterface {

    /**
     * @var \User\Mapper\UserMapperInterface 
     */
    protected $userMapper;

    
    /**
     * @var Passkey for AES128 Encryption 
     */
    protected $key;
    
    /**
     * Function to encrypt with AES128
     * 
     * @param $toEncrypt    password to be encyrpted
     * @param $key          public key to use for encryption
     */
    private function encyptAES128($toEncrypt, $key) {
        //Encrypt
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, trim($toEncrypt), MCRYPT_MODE_ECB));
        //Make ist usable for MySQL
        $encoded = base64_encode($passcrypt);
        return $encoded;
    }

    /**
     * Creates a new instance of the UserPasswordService class.
     * 
     * @param User\Mapper\UserMapperInterface $userMapper
     */
    public function __construct($userMapper) {
        $this->userMapper = $userMapper;
        $this->key ="BadenFahrtPasskey2015";
    }

    /**
     * Encrypts the given password and sets it on the user.
     * 
     * @param \User\Model\User $user
     * @param string $plainPassword
     */
    public function updatePassword($user, $plainPassword) {

        $encryptedPassword = $this->encyptAES128($plainPassword,$this->key);
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
    public function isSatisfied($user, $plainPassword) {
        // Encrypt pwd
        $encryptedPassword = $this->encyptAES128($plainPassword,$this->key);

        return $user->getPassword() == $encryptedPassword;
    }

}
