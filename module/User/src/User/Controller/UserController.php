<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use User\Form\LoginForm;
use Zend\File\Transfer\Adapter\Http as HttpAdapter;
use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;
use Zend\View\Model\JsonModel;

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
        $loginMessage = null;

        if ($request->isPost())
        {
            $loginFailed = true;

            $loginForm->setData($request->getPost());
            if ($loginForm->isValid())
            {
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $user = $userMapper->findByIdentity($loginForm->get('identity')->getValue());
                if ($user && $user->getState() == 0)
                {
                    $resendUrl = $this->url()->fromRoute('user/resendconfirmation', array('identity' => $user->getIdentity()));
                    $loginMessage = "Bitte bestätigen Sie die E-Mail, die wir Ihnen zugesandt haben. "
                            . "Klicken sie auf <a href=\"" . $resendUrl . "\">E-Mail erneut senden</a>, damit wir Ihnen die E-Mail "
                            . "für die Bestätigung erneut zusenden.";
                } else
                {
                    $password = $loginForm->get('password')->getValue();
                    $identity = $loginForm->get('identity')->getValue();

                    $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
                    $authenticationService->getAdapter()->setCredentials($identity, $password);

                    $result = $authenticationService->authenticate();

                    $code = $result->getCode();
                    if ($code == Result::SUCCESS)
                    {
                        return $this->redirect()->toRoute('user/profile');
                    } else
                    {
                        $loginMessage = "Der Benutzername und/oder das Passwort ist falsch.";
                    }
                }
            }
        }

        return new ViewModel(array(
            'loginForm' => $loginForm,
            'loginMessage' => $loginMessage,
        ));
    }

    public function logoutAction()
    {
        $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
        if ($authenticationService->hasIdentity())
        {
            $authenticationService->clearIdentity();
        }

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
        $request = $this->getRequest();

        if (!$request->isXmlHttpRequest())
        {
            return new ViewModel(array());
        }

        return $this->editAction();
    }

    public function editAction()
    {
        $editUserForm = $this->getServiceLocator()->get('edit_user_form');

        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $editUserModel = new ViewModel(array('form' => $editUserForm));
        $editUserModel->setTemplate('editUserModal');
        $editUserModal = $renderer->render($editUserModel);

        $messages = array();
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $formdata = array();
            $ajaxdata = \Zend\Json\Json::decode($request->getContent(), \Zend\Json\Json::TYPE_ARRAY);
            foreach ($ajaxdata as $value)
            {
                $formdata[$value['name']] = $value['value'];
            }

            $editUserForm->setData($formdata);
            if ($editUserForm->isValid())
            {
                $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                $editedUser = $editUserForm->getData();

                if ($editedUser->getPassword())
                {
                    $passwordService = $this->getServiceLocator()->get('User\Service\UserPasswordServiceInterface');
                    $passwordService->updatePassword($editedUser, $editedUser->getPassword());
                }

                $userMapper->save($editedUser);
            }
        } else
        {
            $errors = $editUserForm->getMessages();
            foreach ($errors as $key => $row)
            {
                if (!empty($row) && $key != 'submit')
                {
                    foreach ($row as $keyer => $rower)
                    {
                        $messages[$key][] = $rower;
                    }
                }
            }
        }

        return new JsonModel(array(
            'success' => $messages ? false : true,
            'messages' => $messages,
            'form' => $editUserModal,
        ));
    }

    public function editAvatarAction()
    {
        $form = $this->getServiceLocator()->get('edit_avatar_form');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $avatarFile = $this->params()->fromFiles('avatar');
            $avatarFileName = $avatarFile['name'];

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

            if ($adapter->isValid())
            {
                $adapter->setDestination($this->getAvatarBaseDir());
                if ($adapter->receive($avatarFileName))
                {
                    $avatarPath = $this->getAvatarBaseDir() . '\\' . $avatarFileName;
                    $resizedAvatar = $this->resizeImage($avatarPath, 130, 130);
                    imagejpeg($resizedAvatar, $avatarPath);

                    $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
                    $user = $authenticationService->getIdentity();
                    $user->setAvatar($avatarFileName);

                    $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
                    $userMapper->save($user);
                }
            }else{
                 \Zend\Debug\Debug::dump($adapter->getMessages());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function avatarAction()
    {
        $avatarDir = $this->getAvatarBaseDir() . '\\avatar-placeholder.jpg';
        $user = null;

        if ($this->isAllowed('administrator') && $this->params()->fromRoute('id'))
        {
            $id = $this->params()->fromRoute('id');
            $userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
            $user = $userMapper->findById($id);
        } else
        {
            $authenticationService = $this->getServiceLocator()->get('user_authentication_service');
            $user = $authenticationService->getIdentity();
        }

        if ($user->getAvatar() && file_exists($this->getAvatarBaseDir() . '\\' . $user->getAvatar()))
        {
            $avatarDir = $this->getAvatarBaseDir() . '\\' . $user->getAvatar();
        }

        $fp = fopen($avatarDir, 'rb');
        $size = getimagesize($avatarDir);
        if ($size && $fp)
        {
            header('Content-Type: ' . $size['mime']);
            header('Content-Length: ' . filesize($avatarDir));
            fpassthru($fp);
        }

        $viewModel = new ViewModel(array());
        $viewModel->setTerminal(true);

        return $viewModel();
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

    /**
     * Resize an image and keep the proportions. 
     * Source from http://php.net/manual/de/function.imagecopyresized.php.
     * 
     * @param string $filename
     * @param integer $max_width
     * @param integer $max_height
     * 
     * @return image
     */
    protected function resizeImage($filename, $max_width, $max_height)
    {
        list($orig_width, $orig_height) = getimagesize($filename);

        $width = $orig_width;
        $height = $orig_height;

        // taller
        if ($height > $max_height)
        {
            $width = ($max_height / $height) * $width;
            $height = $max_height;
        }

        // wider
        if ($width > $max_width)
        {
            $height = ($max_width / $width) * $height;
            $width = $max_width;
        }

        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

        return $image_p;
    }

    protected function getAvatarBaseDir()
    {
        return dirname(__DIR__) . '\..\..\avatars';
    }

}
