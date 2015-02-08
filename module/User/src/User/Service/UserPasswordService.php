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
     * @var Passkey for AES Encryption 
     */
    protected $key;
    
    /**
     * Function to encrypt with AES
     * Using MCRYPT_RIJNDAEL_256 algorithm
     * 
     * @param $toEncrypt    password to be encyrpted
     * @param $key          public key to use for encryption
     */
    private function encyptAES($toEncrypt, $key) {
        //Encrypt
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, trim($toEncrypt), MCRYPT_MODE_ECB));
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
        
        $this->key ="BadenFahrtPasskey2015Sem--Arbeit"; // This should be a random string, recommended 32 bytes
    }

    /**
     * Encrypts the given password and sets it on the user.
     * 
     * @param \User\Model\User $user
     * @param string $plainPassword
     */
    public function updatePassword($user, $plainPassword) {

        $encryptedPassword = $this->encyptAES($plainPassword,$this->key);
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
        $encryptedPassword = $this->encyptAES($plainPassword,$this->key);

        return $user->getPassword() == $encryptedPassword;
    }

}
