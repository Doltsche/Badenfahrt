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

    public function __construct($userMapper, array $options = array())
    {
        parent::__construct($options);

        $this->userMapper = $userMapper;
    }

    public function isValid($value, $context = null)
    {
        $value = (string) $value;

        if (!$value)
        {
            $this->error(self::DISPLAYNAMEISEMPTY);
            return false;
        } else if ($this->userMapper->findByDisplayName($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        }

        return true;
    }

}
