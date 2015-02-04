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
    const ISEMPTY = 'isempty';

    protected $messageTemplates = array(
        self::NOTUNIQUE => "Nicht einmalig.",
        self::ISEMPTY => "Leer"
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
        $identity = null;
        if ($this->authenticationService)
        {
            $identity = $this->authenticationService->getIdentity();
        }

        if (!$value)
        {
            $this->error(self::ISEMPTY);
            return false;
        } 
        if ($identity && $identity->getDisplayName() != $value && $this->userMapper->findByDisplayName($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        } 
        if (!($identity) && $this->userMapper->findByDisplayName($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        }

        return true;
    }

}
