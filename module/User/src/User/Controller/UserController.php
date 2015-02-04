<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use User\Form\LoginForm;
use Zend\File\Transfer\Adapter\Http as HttpAdapter;
use User\Form\EditForm;
use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

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
                        // TODO
                        return $this->redirect()->toRoute('user', array('action' => 'confirmPrompt'), array(), true);
                    } else if ($authenticationService->getIdentity()->getState() == 1)
                    {
                        return $this->redirect()->toRoute('user/register/personal');
                    }

                    return $this->redirect()->toRoute('user/profile');
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

    public function manageAction()
    {
        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $users = $userMapper->findAll();

        return new ViewModel(array(
            'users' => $users));
    }

    public function profileAction()
    {
        $runModal = '';
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $editUserForm = $this->getServiceLocator()->get('edit_user_form');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $editUserForm->setData($request->getPost());
            if ($editUserForm->isValid())
            {
                $user = $editUserForm->getData();
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $userMapper->save($user);
            } else
            {
                $runModal = 'editUserModal';
            }
        }

        $editUserModel = new ViewModel(array('form' => $editUserForm));
        $editUserModel->setTemplate('editUserModal');

        $editUserModal = $renderer->render($editUserModel);

        return new ViewModel(array(
            'runModal' => $runModal,
            'editUserModal' => $editUserModal,
        ));
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

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        $authenticationService = $this->getServiceLocator()->get('user_authentication_service');

        $user = $userMapper->findById($id);

        if ($user && $user->getId() != $authenticationService->getIdentity()->getId())
        {
            $userMapper->remove($user);
        }

        return $this->redirect()->toRoute('user/manage');
    }

    protected function prepareAvatar($file, $crop = array('x' => 0, 'y' => 0, 'w' => 100, 'h' => 100))
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
            $avatarDir = dirname(__DIR__) . '\..\..\avatars';
            $adapter->setDestination($avatarDir);

            if ($adapter->receive($file['name']))
            {
                return $avatarDir . '\\' . $file['name'];
            } else
            {
                return '';
            }
        }
    }

}
