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
 * The UserController class.
 */
class UserController extends AbstractActionController
{

    /**
     * @var \User\Mapper\UserMapperInterface
     */
    protected $userMapper;
    
    /**
     * @var \Zend\Authentication\AuthenticationService 
     */
    protected $authenticationService;

    /**
     * Action invoked by route /user.
     * 
     * Login.
     * 
     * @return ViewModel
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $form = new LoginForm();

        if ($request->isPost())
        {
            // Populate the form with the posted data.
            $form->setData($request->getPost());

            // Validate the form.
            if ($form->isValid())
            {
                $user = $this->getUserMapper()->findByIdentity($form->get('identity')->getValue());

                // Check if the authenticated user is allowed to login (did he confirm the email).
                if ($user && $user->getState() == 0)
                {
                    $this->flashMessenger()->addMessage("Bitte bestätigen Sie die E-Mail, die wir Ihnen an " + $user->getIdentity() + " gesandt haben.");
                } else
                {
                    // Fetch the credentials from the form.
                    $password = $form->get('password')->getValue();
                    $identity = $form->get('identity')->getValue();

                    // Pass the credentials to the authentication adapter.
                    $this->getAuthService()->getAdapter()->setCredentials($identity, $password);

                    // Attempt to authenticate.
                    $result = $this->getAuthService()->authenticate();

                    // Check the authentication result.
                    $code = $result->getCode();
                    if ($code == Result::SUCCESS)
                    {
                        // The authentication was successfully. Redirect the user to his profile.
                        return $this->redirect()->toRoute('user/profile');
                    } else
                    {
                        // The provied credentials do not match.
                        $this->flashMessenger()->addWarningMessage("Die E-Mail und/oder das Passwort ist falsch.");

                        // Hack, hack hack...
                        return new ViewModel(array(
                            'form' => $form,
                        ));
                    }
                }
            }
            {
                // The form data was not valid.
                $this->flashMessenger()->addWarningMessage('Bitte geben Sie Ihre E-Mail und das Passwort ein, um sich einzuloggen.');
            }
        }

        // Return the login form.
        return new ViewModel(array(
            'form' => $form,
        ));
    }

    /**
     * Action invoked by route /user/logout.
     * 
     * Logout.
     * 
     * @return ViewModel
     */
    public function logoutAction()
    {
        if ($this->getAuthService()->hasIdentity())
        {
            $this->getAuthService()->clearIdentity();
        }

        return $this->redirect()->toRoute('home');
    }

    /**
     * Action invoked by route /user/manage.
     * 
     * @return ViewModel
     */
    public function manageAction()
    {
        $users = $this->getUserMapper()->findAll();

        return new ViewModel(array(
            'users' => $users));
    }

    /**
     * Action invoked by route /user/profile.
     * 
     * @return ViewModel
     */
    public function profileAction()
    {
        return new ViewModel(array(
            'user' => $this->getAuthService()->getIdentity(),
        ));
    }

    /**
     * Action invoked by route /user/renderUserEditModal[/:id]
     * 
     * Renders a modal dialog that allows to edit a user. The rendered output
     * is then returned in a JSON string. If no id is given, the edit form
     * is populated with the current authenticated user. Otherwise with the user found
     * by the given id (only allowed with administrator role).
     * 
     * @return JsonModel
     */
    public function renderUserEditModalAction()
    {
        $editUserForm = $this->getServiceLocator()->get('edit_user_form');
        $persisted = false;

        $request = $this->getRequest();
        if ($request->isPost())
        {
            // Fetch the posted data.
            $formdata = array();
            $ajaxdata = \Zend\Json\Json::decode($request->getContent(), \Zend\Json\Json::TYPE_ARRAY);
            foreach ($ajaxdata as $value)
            {
                $formdata[$value['name']] = $value['value'];
            }

            // Populate the form with the fetched data.
            $editUserForm->setData($formdata);

            // Validate the form.
            if ($editUserForm->isValid())
            {
                $editedUser = $editUserForm->getData();
                
                $userToSave = $this->getUserMapper()->findById($editedUser->getId());
                $userToSave->setCity($editedUser->getCity());
                $userToSave->setDisplayName($editedUser->getDisplayName());
                $userToSave->setFirstname($editedUser->getFirstname());
                $userToSave->setIdentity($editedUser->getIdentity());
                $userToSave->setLastname($editedUser->getLastname());
                $userToSave->setPhone($editedUser->getPhone());
                $userToSave->setPostalCode($editedUser->getPostalCode());
                $userToSave->setStreetAndNr($editedUser->getStreetAndNr());
                
                // Update the password if it has changed.
                if (strlen($editedUser->getPassword()) > 0)
                {
                    $passwordService = $this->getServiceLocator()->get('User\Service\UserPasswordServiceInterface');
                    $passwordService->updatePassword($userToSave, $editedUser->getPassword());
                }

                // The user is identified by a hidden id field in the form. Ensure that a normal user
                // can not edit another user by changing the id of the hidden field.
                $authenticatedUserId = $this->getAuthService()->getIdentity()->getId();
                if ($authenticatedUserId == $userToSave->getId() || $this->isAllowed('administrator'))
                {
                    $this->getUserMapper()->save($userToSave);
                }

                // Ignore the fact that the user may not be persisted due to invalid authorization.
                $persisted = true;
            }
        } else
        {
            // Get the correct user.
            $user = null;
            if ($this->isAllowed('administrator') && $this->params()->fromRoute('id'))
            {
                $id = $this->params()->fromRoute('id');
                $user = $this->getUserMapper()->findById($id);
            } else
            {
                $user = $this->getAuthService()->getIdentity();
            }

            // Bind the user to the edit form.
            $editUserForm->bind($user);
        }

        // Render the modal dialog.
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        $editUserModel = new ViewModel(array('form' => $editUserForm));
        $editUserModel->setTemplate('editUserModal');
        $editUserModal = $renderer->render($editUserModel);

        return new JsonModel(array(
            'persisted' => $persisted,
            'modal' => $editUserModal,
        ));
    }

    /**
     * Action invoked by route /user/editAvatar[/:id].
     * 
     * Edit the avatar of a user. If the id is given, the avatar of the user 
     * with the given id will be edited (only allowed with administrator role).
     * 
     * @return ViewModel
     */
    public function editAvatarAction()
    {
        $closeUrl = '';
        $avatarUrl = '';
        $uploadUrl = '';

        $user = null;

        $allowed = $this->isAllowed('administrator');
        if ($this->isAllowed('administrator') && $this->params()->fromRoute('id'))
        {
            $id = $this->params()->fromRoute('id');
            $user = $this->getUserMapper()->findById($id);

            $closeUrl = $this->url()->fromRoute('user/manage');
            $avatarUrl = $this->url()->fromRoute('user/avatar', array('id' => $user->getId()));
            $uploadUrl = $this->url()->fromRoute('user/editAvatar', array('id' => $user->getId()));
        } else
        {
            $user = $this->getAuthService()->getIdentity();

            $closeUrl = $this->url()->fromRoute('user/profile');
            $avatarUrl = $this->url()->fromRoute('user/avatar');
            $uploadUrl = $this->url()->fromRoute('user/editAvatar');
        }

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
                $newAvatarFileName = substr(md5(uniqid(mt_rand(), true)), 0, 16) . '.jpg';
                $newAvatarPath = $this->getAvatarBaseDir() . DIRECTORY_SEPARATOR . $newAvatarFileName;

                $adapter->addFilter('File\Rename', array(
                    'target' => $newAvatarPath,
                    'overwrite' => true));

                if ($adapter->receive($avatarFileName))
                {
                    $resizedAvatar = $this->resizeImage($newAvatarPath, 130, 130);
                    imagejpeg($resizedAvatar, $newAvatarPath);

                    $user->setAvatar($newAvatarFileName);
                    $this->getUserMapper()->save($user);
                }
            } else
            {
                foreach ($adapter->getMessages() as $message)
                {
                    $this->flashMessenger()->addErrorMessage($message);
                }
            }
        }

        return new ViewModel(array(
            'avatarUrl' => $avatarUrl,
            'closeUrl' => $closeUrl,
            'uploadUrl' => $uploadUrl,
            'form' => $form,
        ));
    }

    /**
     * Action invoked by route /user/avatar[/:id]
     * 
     * Shows the avatar of the authenticated user with php fpassthru. If the id is given,
     * the avatar of the user with the given id is shown (only allowed with administrator role).
     * 
     * @return ViewModel
     */
    public function avatarAction()
    {
        $avatarDir = $this->getAvatarBaseDir() . '\\avatar-placeholder.jpg';
        $user = null;

        if ($this->isAllowed('administrator') && $this->params()->fromRoute('id'))
        {
            $id = $this->params()->fromRoute('id');
            $user = $this->getUserMapper()->findById($id);
        } else
        {
            $user = $this->getAuthService()->getIdentity();
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

    /**
     * Action invoked by route /user/delete[/:id]
     * 
     * Deletes the current authenticated user 
     * or the user found by the given id (only allowed with administrator role).
     * 
     * @return ViewModel
     */
    public function deleteAction()
    {
        $user = null;
        $redirectRoute = 'home';

        if ($this->isAllowed('administrator') && $this->params()->fromRoute('id'))
        {
            $id = $this->params()->fromRoute('id');
            $user = $this->getUserMapper()->findById($id);

            $redirectRoute = 'user/manage';
        } else
        {
            $user = $this->getAuthService()->getIdentity();
        }

        if ($user)
        {
            $this->getUserMapper()->remove($user);
        }

        return $this->redirect()->toRoute($redirectRoute);
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

    /**
     * Returns the path of the directory for the avatars.
     * 
     * @return string
     */
    protected function getAvatarBaseDir()
    {
        return dirname(__DIR__) . '\..\..\avatars';
    }

    /**
     * Returns an implementation of the UserMapperInterface interface.
     * 
     * @return \User\Mapper\UserMapperInterface.
     */
    protected function getUserMapper()
    {
        if (!$this->userMapper)
        {
            $this->userMapper = $this->getServiceLocator()->get('User\Mapper\UserMapperInterface');
        }

        return $this->userMapper;
    }

    /**
     * Returns an instance of the AuthenticationService class.
     * 
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getAuthService()
    {
        if (!$this->authenticationService)
        {
            $this->authenticationService = $this->getServiceLocator()->get('user_authentication_service');
        }

        return $this->authenticationService;
    }

}
