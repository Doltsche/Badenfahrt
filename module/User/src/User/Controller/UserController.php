<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use User\Form\LoginForm;
use User\Form\RegisterForm;
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
                    return $this->redirect()->toRoute('home');
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
        $form = new RegisterForm();
        $user = new User();
        $registrationError = '';

        // test values;
        $user->setId(0);
        $user->setIdentity('samuel.egger@gmail.com');
        $user->setPassword('test123');
        $user->setDisplayName('UVAS');
        $user->setFirstname('Samuel');
        $user->setLastname('Egger');
        $user->setStreetAndNr('Parkstrasse 1');
        $user->setCity('Ostermundigen');
        $user->setPostalCode('3072');
        $user->setPhone('0794288465');

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

        $form->bind($user);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $user = $form->getData();
                if ($userMapper->findByIdentity($user->getIdentity()))
                {
                    $registrationError = "Die E-Mail '{$user->getIdentity()}' wurde bereits registriert.";

                    $user->setIdentity('');
                    $user->setIdentityVerify('');
                    $form->bind($user);
                } else if ($userMapper->findByDisplayName($user->getDisplayName()))
                {
                    $registrationError = "Der Anzeigename '{$user->getDisplayName()}' wurde bereits registriert.";

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

                    $userMapper->save($user);
                }
            }
        }

        $msgs = $form->getMessages();
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
        return new ViewModel(array('token' => $token));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        return new ViewModel(array('id' => $id));
    }

}
