<?php

namespace User\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Description of BlogAuthAdapter
 *
 * @author Dev
 */
class UserAuthenticationAdapter implements AdapterInterface
{

    protected $userMapper;
    protected $identity;
    protected $password;
    protected $user;
    protected $passwordService;

    public function __construct($userMapper, $passwordService)
    {
        $this->userMapper = $userMapper;
        $this->passwordService = $passwordService;
    }

    public function authenticate()
    {
        if ($this->user)
        {
            return new Result(Result::SUCCESS, $user->getId(), array());
        }

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

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setCredentials($identity, $password)
    {
        $this->identity = $identity;
        $this->password = $password;
    }

}
