<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;
use User\Model\Personal;

/**
 * Description of UserController
 *
 * @author Dev
 */
class RegisterController extends AbstractActionController
{

    protected $registeredUser;

    public function registerUserAction()
    {
        $error = '';

        $form = $this->getServiceLocator()->get('register_user_form');
        $form->bind(new User());

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setData($request->getPost());
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

                $this->registeredUser = $user;

                // Prompt the user to confirm the registration request.
                return $this->forward()->dispatch('User\Controller\Register', array('action' => 'confirmPrompt'));
            }
        }

        return array(
            'form' => $form,
            'error' => $error,
        );
    }

    public function registerPersonalAction()
    {
        $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
        if (!$authenticationService->hasIdentity())
        {
            return $this->redirect()->toRoute('user');
        }

        $error = '';

        $form = $this->getServiceLocator()->get('register_personal_form');
        $form->bind(new Personal());

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $personalMapper = $this->getServiceLocator()->get('User\Mapper\PersonalMapperInterface');
                $personal = $form->getData();
                $personalMapper->save($personal);

                $user = $authenticationService->getIdentity();
                $user->setPersonal($personal);

                // Set role for confirmed user.
                $roleMapper = $this->getServiceLocator()->get('User\Mapper\RoleMapperInterface');
                $userRole = $roleMapper->findByRoleId('user');
                $user->addRole($userRole);

                // Remove the token from the user.
                $user->setToken(NULL);

                // Active the user
                $user->setState(2);

                // Save the user.
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $userMapper->save($user);

                // Go to the profile of the user.
                return $this->redirect()->toRoute('user/profile');
            }
        }

        return array(
            'form' => $form,
            'error' => $error,
        );
    }

    public function registerSuccessAction()
    {
        return new ViewModel(array(
        ));
    }

    public function confirmAction()
    {
        $token = $this->params()->fromRoute('token');

        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $user = $userMapper->findByToken($token);
        if ($user)
        {
            $user->setState(1);
            $userMapper->save($user);
        }

        return $this->redirect()->toRoute('user');
    }

    public function confirmPromptAction()
    {
        return new ViewModel(array(
            'identity' => $this->registeredUser->getIdentity(),
        ));
    }

}
