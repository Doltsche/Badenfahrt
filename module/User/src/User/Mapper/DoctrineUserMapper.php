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

    public function findByIdentity($identity)
    {
        $userRepository = $this->entityManager->getRepository('User\Model\User');
        $user = $userRepository->findOneBy(array('identity' => $identity));
        
        // Some workaround for Doctrine problem.
        if ($user)
        {
            $user->getRoles();
        }

        return $user;
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
