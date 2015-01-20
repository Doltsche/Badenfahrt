<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ApplicationController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
	
	public function kontaktAction()
    {
        return new ViewModel();
    }
	
	public function registerAction()
    {
        return new ViewModel();
    }
	
	public function faqAction()
    {
        return new ViewModel();
    }
	
	public function administrationAction()
    {
        return new ViewModel();
    }
}
