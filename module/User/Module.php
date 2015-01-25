<?php

namespace User;

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

}
