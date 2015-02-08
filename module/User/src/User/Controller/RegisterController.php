<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;

/**
 * Description of UserController
 *
 * @author Dev
 */
class RegisterController extends AbstractActionController
{

    public function registerAction()
    {
        $success = false;
        $user = null;

        $form = $this->getServiceLocator()->get('register_user_form');
        $form->bind(new User());

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setData($request->getPost());
            $isValid = $form->isValid();
            if ($form->isValid())
            {
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $user = $form->getData();

                // Set role for registered, but not yet confirmed user.
                $roleMapper = $this->getServiceLocator()->get('User\Mapper\RoleMapperInterface');
                $registeredRole = $roleMapper->findByRoleId('registered');
                $user->addRole($registeredRole);

                // Generate random token for email confirmation.
                $token = md5(uniqid(mt_rand(), true));
                $user->setToken($token);

                $passwordService = $this->getServiceLocator()->get('User\Service\UserPasswordServiceInterface');
                $passwordService->updatePassword($user, $user->getPassword());

                // Save the registration.
                $userMapper->save($user);

                // Send confirmation request.
                $userMailService = $this->getServiceLocator()->get('User\Service\UserMailServiceInterface');
                $userMailService->sendConfirmationRequest($user);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'user' => $user,
        ));
    }

    /**
     * Action invoked by route /user/resendconfirmation/:identity.
     * 
     * Sends the user a new email to confirm his registration.
     */
    public function resendConfirmationAction()
    {
        $identity = $this->params()->fromRoute('identity');

        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $user = $userMapper->findByIdentity($identity);
        if ($user && $user->getState() == 0)
        {
            // Update token for security reason.
            $token = md5(uniqid(mt_rand(), true));
            $user->setToken($token);
            $userMapper->save($user);

            // Resend confirmation request.
            $userMailService = $this->getServiceLocator()->get('User\Service\UserMailServiceInterface');
            $userMailService->sendConfirmationRequest($user);
        }

        // Redirect to the login page.
        return $this->redirect()->toRoute('user');
    }

    /**
     * Action invoked by route /user/conform/:token
     * 
     * Confirms the validity of the registered email of a user
     * and elevates the state and role of the user to allow him to login.
     */
    public function confirmAction()
    {
        $token = $this->params()->fromRoute('token');

        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $user = $userMapper->findByToken($token);
        if ($user && $user->getState() == 0)
        {
            // Give the user the user role.
            $roleMapper = $this->getServiceLocator()->get('User\Mapper\RoleMapperInterface');
            $userRole = $roleMapper->findByRoleId('user');
            $user->addRole($userRole);

            // Set state for confirmed user.
            $user->setState(1);
            $userMapper->save($user);

            $this->flashMessenger()->addSuccessMessage("Ihre E-Mail wurde erfolgreich bestätigt. Sie können sich jetzt einloggen.");
        }

        return $this->redirect()->toRoute('user');
    }

}
