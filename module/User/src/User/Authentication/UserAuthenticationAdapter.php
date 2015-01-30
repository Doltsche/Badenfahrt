<?php

namespace User\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

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

    public function __construct($userMapper)
    {
        $this->userMapper = $userMapper;
    }
    
    public function authenticate()
    {
        $user = $this->userMapper->findByIdentity($this->identity);

        if (!$user)
        {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $user, array());
        }

        if ($user && $user->getPassword() == $this->password)
        {
            return new Result(Result::SUCCESS, $user, array());
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, $user, array());
    }

    public function setCredentials($identity, $password)
    {
        $this->identity = $identity;
        $this->password = $password;
    }

}
