<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

class Module
{

    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger;
        $this->logger->addWriter(new Stream(__DIR__ . '/../../log/error.log'));

        \Zend\Log\Logger::registerErrorHandler($this->logger);
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Handle the dispatch error (exception).
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'));

        // Handle the view render error (exception).
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR, array($this, 'handleError'));

        $translator = $e->getApplication()->getServiceManager()->get('translator2');
        $translator->addTranslationFile('phpArray', __DIR__ . '\\..\..\\vendor\\zendframework\\zendframework\\resources\\languages\\de\\Zend_Validate.php', 'default', 'de_DE');

        \Zend\Validator\AbstractValidator::setDefaultTranslator(new \Zend\Mvc\I18n\Translator($translator));
    }

    public function handleError(MvcEvent $e)
    {
        $exception = $e->getParam('exception');
        $this->logger->log(\Zend\Log\Logger::ERR, $exception->getMessage());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
