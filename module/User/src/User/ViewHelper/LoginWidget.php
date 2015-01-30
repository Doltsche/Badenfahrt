<?php

namespace User\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

class LoginWidget extends AbstractHelper
{

    protected $loginView;
    protected $loginForm;
    protected $logoutView;

    public function __construct($loginView, $loginForm, $logoutView)
    {
        $this->loginView = $loginView;
        $this->loginForm = $loginForm;
        $this->logoutView = $logoutView;
    }

    public function __invoke()
    {
        $vm = new ViewModel(array('loginForm' => $this->loginForm,));
        $vm->setTemplate($this->loginView);

        return $this->getView()->render($vm);
    }

}
