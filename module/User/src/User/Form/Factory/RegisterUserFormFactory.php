<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\RegisterUserForm;
use User\Form\Filter\RegisterUserFilter;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class RegisterUserFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');

        $registerFilter = new RegisterUserFilter($userMapper);

        $registerForm = new RegisterUserForm();
        $registerForm->setInputFilter($registerFilter);

        return $registerForm;
    }

}
