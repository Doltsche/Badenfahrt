<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\RegisterUserForm;
use User\Form\Filter\RegisterUserFilter;

/**
 * The factory creates an instance of the RegisterUserForm class.
 */
class RegisterUserFormFactory implements FactoryInterface
{

    /**
     * Creates an instance of the RegisterUserForm class.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return RegisterUserForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
        $registerFilter = new RegisterUserFilter($userMapper);

        $registerForm = new RegisterUserForm();
        $registerForm->setInputFilter($registerFilter);

        return $registerForm;
    }

}
