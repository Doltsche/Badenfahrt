<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use User\Form\LoginForm;
use Zend\File\Transfer\Adapter\Http as HttpAdapter;
use User\Form\EditForm;
use User\Model\User;
use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;

/**
 * Description of UserController
 *
 * @author Dev
 */
class UserController extends AbstractActionController
{

    public function loginAction()
    {
        $request = $this->getRequest();
        $loginForm = new LoginForm();
        $loginFailed = false;

        if ($request->isPost())
        {
            $loginFailed = true;

            $loginForm->setData($request->getPost());
            if ($loginForm->isValid())
            {
                try
                {
                    $password = $loginForm->get('password')->getValue();
                    $identity = $loginForm->get('identity')->getValue();

                    $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
                    $authenticationService->getAdapter()->setCredentials($identity, $password);

                    $result = $authenticationService->authenticate();
                } catch (Exception $ex)
                {
                    $result = Result::FAILURE_UNCATEGORIZED;
                }

                $code = $result->getCode();
                if ($code == Result::SUCCESS)
                {
                    if ($authenticationService->getIdentity()->getState() == 0)
                    {
                        return $this->redirect()->toRoute('user', array('action' => 'confirmPrompt'), array(), true);
                    }

                    return $this->redirect()->toRoute('home', array('action' => ''), array(), true);
                }
            }
        }

        return new ViewModel(array(
            'loginForm' => $loginForm,
            'loginFailed' => $loginFailed,
        ));
    }

    public function logoutAction()
    {
        $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
        $authenticationService->clearIdentity();

        return $this->redirect()->toRoute('home');
    }

    public function registerAction()
    {
        $registrationError = '';

        $form = $this->getServiceLocator()->get('user_register_form');
        $form->bind(new User());

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $user = $form->getData();
                $this->handleAvatar($user);

                $roleMapper = $this->getServiceLocator()->get('User\Mapper\RoleMapperInterface');
                $registeredRole = $roleMapper->findByRoleId('registered');

                $user->addRole($registeredRole);
                $userMapper->save($user);

                // TODO: Generate real token
                $user->setToken($user->getId());
                $userMapper->save($user);

                $userMailService = $this->getServiceLocator()->get('User\Service\UserMailServiceInterface');
                $userMailService->sendConfirmationRequest($user);
            }
        }

        return array(
            'registerForm' => $form,
            'registrationError' => $registrationError,
        );
    }

    public function manageAction()
    {
        return new ViewModel();
    }

    public function editAction()
    {
        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
        if (!$authenticationService->hasIdentity())
        {
            return $this->redirect()->toRoute('user');
        }

        $editError = false;
        $form = new EditForm();

        $user = $userMapper->findById($authenticationService->getIdentity()->getId());
        $oldIdentity = $user->getIdentity();
        $oldDisplayName = $user->getDisplayName();

        $form->bind($user);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            if ($request->getPost()['user-fieldset']['password'])
            {
                $user->getInputFilter()->add(array(
                    'name' => 'password',
                    'required' => true,
                    'filters' => array(array('name' => 'StringTrim')),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 6,
                            ),
                        ),
                    ),
                ));

                $user->getInputFilter()->add(array(
                    'name' => 'passwordVerify',
                    'required' => true,
                    'filters' => array(array('name' => 'StringTrim')),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 6,
                            ),
                        ),
                    ),
                ));
            }

            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $user = $form->getData();
                if ($user->getIdentity() != $oldIdentity && $userMapper->findByIdentity($user->getIdentity()))
                {
                    $editError = "Die E-Mail '{$user->getIdentity()}' wurde bereits registriert.";
                    $user->setIdentity('');
                    $user->setIdentityVerify('');
                    $form->bind($user);
                } else if ($user->getDisplayName() != $oldDisplayName && $userMapper->findByDisplayName($user->getDisplayName()))
                {
                    $editError = "Der Anzeigename '{$user->getDisplayName()}' wurde bereits registriert.";
                    $user->setDisplayName('');
                    $form->bind($user);
                } else
                {
                    if ($user->getAvatar())
                    {
                        // IMPORTANT: Enable the php_fileinfo.dll extension.
                        $isImageValidator = new MimeType();
                        $isImageValidator->setMimeType(array(
                            'image/png',
                            'image/jpeg',
                        ));

                        $adapter = new \Zend\File\Transfer\Adapter\Http();
                        $adapter->setValidators(array(
                            $isImageValidator,
                            new Size(array('min' => 1024, 'max' => 51200,)),
                        ));

                        if (!$adapter->isValid())
                        {
                            $form->get('user-fieldset')->setMessages(array('avatar' => $adapter->getMessages()));
                        } else
                        {
                            $avatarDir = dirname(__DIR__) . '/Assets';
                            $adapter->setDestination($avatarDir);
                            if ($adapter->receive($user->getAvatar()))
                            {
                                $user->setAvatar($avatarDir . '/' . $user->getAvatar());
                            } else
                            {
                                $messages = $adapter->getMessages();
                            }
                        }
                    }

                    // $userMapper->save($user);
                }
            }
        }

        $msgs = $form->getMessages();
        return array(
            'editForm' => $form,
            'editError' => $editError,
        );
    }

    public function confirmAction()
    {
        $token = $this->params()->fromRoute('token');

        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $user = $userMapper->findByToken($token);
        if ($user)
        {
            $roleMapper = $this->getServiceLocator()->get('User\Mapper\RoleMapperInterface');
            $userRole = $roleMapper->findByRoleId('user');

            $user->addRole($userRole);
            $user->setState(1);

            $userMapper->save($user);
        }

        return $this->redirect()->toRoute('user');
    }

    public function confirmPromptAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        return new ViewModel(array('id' => $id));
    }

    protected function handleAvatar($user, $crop = array('x' => 0, 'y' => 0, 'w' => 100, 'h' => 100))
    {
        if ($user->getAvatar())
        {
            // IMPORTANT: Enable the php_fileinfo.dll extension.
            $isImageValidator = new MimeType();
            $isImageValidator->setMimeType(array(
                'image/png',
                'image/jpeg',
            ));

            $adapter = new HttpAdapter();
            $adapter->setValidators(array(
                $isImageValidator,
                new Size(array('min' => 1024, 'max' => 51200,)),
            ));

            if (!$adapter->isValid())
            {
                return false;
            } else
            {
                $avatarDir = dirname(__DIR__) . '../../avatars';
                $adapter->setDestination($avatarDir);

                if ($adapter->receive($user->getAvatar()))
                {
                    $user->setAvatar($avatarDir . '/' . $user->getAvatar());
                }
            }
        }
    }

}
