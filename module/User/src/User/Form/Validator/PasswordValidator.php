<?php

namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Used to validate the password field.
 */
class PasswordValidator extends AbstractValidator
{

    const NOTMATCH = 'notmatch';

    protected $messageTemplates = array(
        self::NOTMATCH => "Die eingegebenen Passwörter stimmen nicht überein.",
    );

    public function isValid($value, $context = null)
    {

        $value = (string) $value;

        if (isset($context['password']) && strlen($context['passwordVerify']) == 0)
        {
            $this->error(self::NOTMATCH);
            return false;
        }

        return true;
    }

}
