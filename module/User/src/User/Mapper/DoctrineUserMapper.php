<?php

namespace User\Mapper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of UserMapper
 *
 * @author Dev
 */
class DoctrineUserMapper implements UserMapperInterface
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        $userRepository = $this->entityManager->getRepository('User\Model\User');
        $users = $userRepository->findAll();

        return $users;
    }

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

    public function save($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function remove($user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

}
