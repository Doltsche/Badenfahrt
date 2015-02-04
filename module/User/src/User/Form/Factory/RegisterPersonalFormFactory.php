<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\RegisterPersonalForm;
use User\Form\Filter\RegisterPersonalFilter;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author Dev
 */
class RegisterPersonalFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');

        $registerFilter = new RegisterPersonalFilter($userMapper);

        $registerForm = new RegisterPersonalForm();
        $registerForm->setInputFilter($registerFilter);

        return $registerForm;
    }

}
