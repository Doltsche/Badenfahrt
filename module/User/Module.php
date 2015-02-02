<?php

namespace User;

use BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider;

/**
 * Description of Module
 *
 * @author Dev
 */
class Module
{

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
//        $sm = $e->getApplication()->getServiceManager();
//
//        // Add ACL information to the Navigation view helper
//        $authorize = $sm->get('BjyAuthorizeServiceAuthorize');
//        $acl = $authorize->getAcl();
//        $role = $authorize->getIdentity();
//        \Zend\View\Helper\Navigation::setDefaultAcl($acl);
//        \Zend\View\Helper\Navigation::setDefaultRole($role);
    }

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

}
