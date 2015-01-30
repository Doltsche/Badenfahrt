<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of UserController
 *
 * @author Dev
 */
class UserController extends AbstractActionController
{

    public function loginAction()
    {
        return new ViewModel();
    }

    public function registerAction()
    {
        return new ViewModel();
    }

    public function manageAction()
    {
        return new ViewModel();
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        
        return new ViewModel(array('id' => $id));
    }

    public function confirmAction()
    {
        $token = $this->params()->fromRoute('token');
        
        return new ViewModel(array('token' => $token));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        
        return new ViewModel(array('id' => $id));
    }

}
