<?php

namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Used to validate the verify password.
 */
class PasswordVerifyValidator extends AbstractValidator
{

    const NOTMATCH = 'notmatch';
    const PASSWORDISEMPTY = 'isempty';

    protected $messageTemplates = array(
        self::NOTMATCH => "Repeated password don't match GUGUS",
        self::PASSWORDISEMPTY => "You have to fill password field GUGUS"
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    public function isValid($value, $context = null)
    {
        $value = (string) $value;

        if (strlen($context['password']) > 0)
        {
            if (!$value)
            {
                $this->error(self::PASSWORDISEMPTY);
                return false;
            } else if ($value != $context['password'])
            {
                $this->error(self::NOTMATCH);
                return false;
            }
        }

        return true;
    }

}
