<?php

namespace User\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * The UserAuthenticationAdapter class.
 */
class UserAuthenticationAdapter implements AdapterInterface
{

    protected $userMapper;
    protected $identity;
    protected $password;
    protected $passwordService;

    /**
     * Initializes a new instance of the UserAuthenticationAdapter class.
     * 
     * @param \User\Mapper\UserMapperInterface $userMapper
     * @param \User\Service\UserPasswordServiceInterface $passwordService
     */
    public function __construct($userMapper, $passwordService)
    {
        $this->userMapper = $userMapper;
        $this->passwordService = $passwordService;
    }

    /**
     * Authenticate by the set credentials.
     * 
     * @return Result
     */
    public function authenticate()
    {
        $user = $this->userMapper->findByIdentity($this->identity);

        if (!$user)
        {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, 0, array());
        }
        
        if ($this->passwordService->isSatisfied($user, $this->password))
        {
            return new Result(Result::SUCCESS, $user->getId(), array());
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, 0, array());
    }
    
    /**
     * Set the login credentials.
     * 
     * @param string $identity
     * @param string $password
     */
    public function setCredentials($identity, $password)
    {
        $this->identity = $identity;
        $this->password = $password;
    }

}
