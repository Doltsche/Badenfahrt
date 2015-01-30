<?php

namespace User\ViewHelper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\ViewHelper\LoginWidget;
use User\Form\LoginForm;

/**
 * Description of LoginViewHelperFactory
 *
 * @author Dev
 */
class LoginWidgetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $loginView = "user\user\login.phtml";
        $logoutView = "user\user\logout.phtml";
        $loginForm = new LoginForm();
        
        return new LoginWidget($loginView, $loginForm, $logoutView);
    }
}
