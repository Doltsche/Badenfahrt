<?php

namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Used to validate the identity.
 */
class IdentityUniqueValidator extends AbstractValidator
{

    const NOTUNIQUE = 'notunique';
    const ISEMPTY = 'isempty';

    protected $messageTemplates = array(
        self::NOTUNIQUE => "Die angegebene E-Mail wird bereits verwendet.",
        self::ISEMPTY => "Das Feld darf nicht leer sein."
    );
    
    protected $userMapper;

    public function __construct($userMapper, array $options = array())
    {
        parent::__construct($options);

        $this->userMapper = $userMapper;
    }

    public function isValid($value, $context = null)
    {
        $identity = null;
        if (context['id'])
        {
            $identity = $this->userMapper->findById(context['id']);
        }

        if (!$value)
        {
            $this->error(self::ISEMPTY);
            return false;
        } 
        if ($identity && $identity->getIdentity() != $value && $this->userMapper->findByIdentity($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        } 
        if (!($identity) && $this->userMapper->findByIdentity($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        }

        return true;
    }

}
