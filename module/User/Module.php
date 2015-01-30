<?php

namespace User;

<<<<<<< HEAD
=======
use BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider;

>>>>>>> 41235eb
/**
 * Description of Module
 *
 * @author Dev
 */
class Module
{

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array();
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

<<<<<<< HEAD
=======
    public function getServiceConfig()
    {
        return array(
            'User\Authentication\IdentityProvider' => function($sm)
            {
                $simpleIdentityProvider = new AuthenticationIdentityProvider(new AuthenticationService());
                $config = $sm->get('BjyAuthorize\Config');
                $simpleIdentityProvider->setDefaultRole($config['default_role']);
                $simpleIdentityProvider->setAuthenticatedRole($config['authenticated_role']);
                
                return $simpleIdentityProvider;
            },);
    }

>>>>>>> 41235eb
}
