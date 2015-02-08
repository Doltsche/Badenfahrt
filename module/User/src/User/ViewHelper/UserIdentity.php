<?php

namespace User\ViewHelper;

use Zend\View\Helper\AbstractHelper;

/**
 * ViewHelper to show the logged in user on every page.
 */
class UserIdentity extends AbstractHelper
{

    /**
     * @var \Zend\Authentication\AuthenticationService 
     */
    protected $authenticationService;

    /**
     * Creates a new instance of the UserIdentity class.
     * 
     * @param \Zend\Authentication\AuthenticationService $authenticationService
     */
    public function __construct($authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Returns the authenticated user or null if none is authenticated.
     * 
     * @return \User\Model\User
     */
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
