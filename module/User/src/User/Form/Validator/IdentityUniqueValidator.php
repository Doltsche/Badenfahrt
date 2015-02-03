<?php

namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Description of PasswordValidator
 *
 * @author Dev
 */
class IdentityUniqueValidator extends AbstractValidator
{

    const NOTUNIQUE = 'notunique';
    const IDENTIYISEMPTY = 'isempty';

    protected $messageTemplates = array(
        self::NOTUNIQUE => "Nicht einmalig.",
        self::IDENTIYISEMPTY => "Leer"
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
            $this->error(self::IDENTIYISEMPTY);
            return false;
        } else if ($this->userMapper->findByIdentity($value))
        {
            $this->error(self::NOTUNIQUE);
            return false;
        }

        return true;
    }

}
