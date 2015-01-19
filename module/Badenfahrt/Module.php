<?php

namespace Badenfahrt;
 
class Module 
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
 
    public function getAutoloaderConfig()
    {
		// Wird vom Composer erledigt.
		return array();
    }
}