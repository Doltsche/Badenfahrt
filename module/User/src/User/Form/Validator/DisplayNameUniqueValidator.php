<?php

namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Description of PasswordValidator
 *
 * @author Dev
 */
class DisplayNameUniqueValidator extends AbstractValidator
{

    const NOTUNIQUE = 'notunique';
    const DISPLAYNAMEISEMPTY = 'isempty';

    protected $messageTemplates = array(
        self::NOTUNIQUE => "Nicht einmalig.",
        self::DISPLAYNAMEISEMPTY => "Leer"
    );
    protected $userMapper;
    protected $authenticationService;

    public function __construct($userMapper, $authenticationService = null, array $options = array())
    {
        parent::__construct($options);

        $this->userMapper = $userMapper;
        $this->authenticationService = $authenticationService;
    }

    public function isValid($value, $context = null)
    {
        $value = (string) $value;

        $identity = $this->authenticationService->getIdentity();


        if (!$value)
        {
            $this->error(self::DISPLAYNAMEISEMPTY);
            return false;
        } else if ($identity && $identity->getDisplayName() != $value && $this->userMapper->findByDisplayName($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        } else if (!$this->authenticationService->hasIdentity() && $this->userMapper->findByDisplayName($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        }

        return true;
    }

}
