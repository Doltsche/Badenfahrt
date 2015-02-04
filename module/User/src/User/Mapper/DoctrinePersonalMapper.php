<?php

namespace User\Mapper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of UserMapper
 *
 * @author Dev
 */
class DoctrinePersonalMapper implements PersonalMapperInterface
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        $userRepository = $this->entityManager->getRepository('User\Model\Personal');
        $personalData = $userRepository->findAll();

        return $personalData;
    }

    public function findById($id)
    {
        return $this->entityManager->find('User\Model\Personal', $id);
    }

    public function save($personal)
    {
        $this->entityManager->persist($personal);
        $this->entityManager->flush();
    }

    public function remove($personal)
    {
        $this->entityManager->remove($personal);
        $this->entityManager->flush();
    }

}
