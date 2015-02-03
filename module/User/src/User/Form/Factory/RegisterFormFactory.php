<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\RegisterForm;
use User\Form\Filter\RegisterFilter;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class RegisterFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');

        $registerFilter = new RegisterFilter($userMapper);

        $registerForm = new RegisterForm();
        $registerForm->setInputFilter($registerFilter);

        return $registerForm;
    }

}
