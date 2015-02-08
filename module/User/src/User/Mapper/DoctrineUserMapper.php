<?php

namespace User\Mapper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * The class implements the RoleMapperInterface interface using doctrine orm.
 */
class DoctrineUserMapper implements UserMapperInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Creates a new instance of the DoctrineUserMapper class.
     * 
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Finds all User objects.
     * 
     * @return array
     */
    public function findAll()
    {
        $userRepository = $this->entityManager->getRepository('User\Model\User');
        $users = $userRepository->findAll();

        return $users;
    }

    /**
     * Finds a User object by the given id.
     * 
     * @param int $id
     * @return \User\Model\User
     */
    public function findById($id)
    {
        $tmpUser = $this->entityManager->find('User\Model\User', $id);

        // Some workaround for Doctrine problem.
        if ($tmpUser)
        {
            $user = $this->entityManager->find('User\Model\User', $tmpUser->getId());
            $roles = $user->getRoles();
            return $user;
        }

        return null;
    }

    /**
     * Finds a User object by the given identity.
     * 
     * @param string $identity
     * @return \User\Model\User
     */
    public function findByIdentity($identity)
    {
        $userRepository = $this->entityManager->getRepository('User\Model\User');
        $tmpUser = $userRepository->findOneBy(array('identity' => $identity));

        // Some workaround for Doctrine problem.
        if ($tmpUser)
        {
            $user = $this->entityManager->find('User\Model\User', $tmpUser->getId());
            $roles = $user->getRoles();
            return $user;
        }

        return null;
    }

    /**
     * Finds a User object by the givne display name.
     * 
     * @param string $displayName
     * @return \User\Model\User
     */
    public function findByDisplayName($displayName)
    {
        $userRepository = $this->entityManager->getRepository('User\Model\User');
        $tmpUser = $userRepository->findOneBy(array('displayName' => $displayName));

        // Some workaround for Doctrine problem.
        if ($tmpUser)
        {
            $user = $this->entityManager->find('User\Model\User', $tmpUser->getId());
            $roles = $user->getRoles();
            return $user;
        }

        return null;
    }

    /**
     * Finds a User object by the given token.
     * 
     * @param string $token
     * @return \User\Mapper\User
     */
    public function findByToken($token)
    {
        $userRepository = $this->entityManager->getRepository('User\Model\User');
        $tmpUser = $userRepository->findOneBy(array('token' => $token));

        // Some workaround for Doctrine problem.
        if ($tmpUser)
        {
            $user = $this->entityManager->find('User\Model\User', $tmpUser->getId());
            $roles = $user->getRoles();
            return $user;
        }

        return null;
    }

    /**
     * Saves the given User object.
     * 
     * @param \User\Model\User $user
     */
    public function save($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Removes the given user object.
     * 
     * @param \User\Model\User $user
     */
    public function remove($user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

}
