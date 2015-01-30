<?php

namespace User\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class UserIdentity extends AbstractHelper
{

    protected $authenticationService;

    public function __construct($authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function __invoke()
    {
        if ($this->authenticationService->hasIdentity())
        {
            return $this->authenticationService->getIdentity();
        } else
        {
            return null;
        }
    }

}
