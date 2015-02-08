<?php

namespace User\Authentication;

use Zend\Authentication\Storage;
use Zend\Authentication\Storage\StorageInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Mapper\UserInterface as UserMapper;

/**
 * Copied and modified from ZfcUser module.
 * 
 * This storage adapter only stores the id of the authenticated User object
 * in the session and not the entire User object.
 */
class UserAuthenticationStorage implements Storage\StorageInterface
{

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var mixed
     */
    protected $resolvedIdentity;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function __construct($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If it is impossible to determine whether
     * storage is empty or not
     * @return boolean
     */
    public function isEmpty()
    {
        if ($this->getStorage()->isEmpty())
        {
            return true;
        }
        $identity = $this->read();
        if ($identity === null)
        {
            $this->clear();
            return true;
        }
        return false;
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        if (null !== $this->resolvedIdentity)
        {
            return $this->resolvedIdentity;
        }

        $identity = $this->getStorage()->read();

        if (is_int($identity) || is_scalar($identity))
        {
            $identity = $this->userMapper->findById($identity);
        }
        if ($identity)
        {
            $this->resolvedIdentity = $identity;
        } else
        {
            $this->resolvedIdentity = null;
        }

        return $this->resolvedIdentity;
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->write($contents);
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->clear();
    }

    /**
     * getStorage
     *
     * @return Storage\StorageInterface
     */
    public function getStorage()
    {
        if (null === $this->storage)
        {
            $this->setStorage(new Storage\Session);
        }
        return $this->storage;
    }

    /**
     * setStorage
     *
     * @param Storage\StorageInterface $storage
     * @access public
     * @return Db
     */
    public function setStorage(Storage\StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

}
