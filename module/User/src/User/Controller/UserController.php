<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use User\Form\LoginForm;
use User\Form\RegisterForm;
use User\Model\User;
use Zend\InputFilter\Factory as InputFilterFactory;
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

        if ($request->isPost())
        {
            $loginForm->setData($request->getPost());

            if ($loginForm->isValid())
            {
                $result = Result::FAILURE_UNCATEGORIZED;

                try
                {
                    $password = $loginForm->get('password')->getValue();
                    $identity = $loginForm->get('identity')->getValue();

                    $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
                    $authenticationService->getAdapter()->setCredentials($identity, $password);

                    $result = $authenticationService->authenticate();
                } catch (Exception $ex)
                {
                    
                }

                if ($result->getCode() == Result::SUCCESS)
                {
                    return $this->redirect()->toRoute('home');
                } else
                {
                    return new ViewModel(array(
                        'loginForm' => $loginForm,
                        'loginFailed' => true,
                    ));
                }
            }
        }

        return new ViewModel(array(
            'loginForm' => $loginForm,
            'loginFailed' => false,
        ));
    }

    public function logoutAction()
    {
        $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
        $authenticationService->clearIdentity();
    }

    public function registerAction()
    {
        $form = new RegisterForm();
        $user = new User();

        // test values;
        $user->setIdentity('samuel.egger@gmail.com');
        $user->setPassword('test123');
        $user->setDisplayName('UVAS');
        $user->setFirstname('Samuel');
        $user->setLastname('Egger');
        $user->setStreetAndNr('Parkstrasse 1');
        $user->setCity('Ostermundigen');
        $user->setPostalCode('3072');
        $user->setPhone('0794288465');

        $form->bind($user);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $user = $form->getData();

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

                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $userMapper->save($user);
            }
        }

        return array('form' => $form);
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
