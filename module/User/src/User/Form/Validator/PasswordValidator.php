<?php

namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Description of PasswordValidator
 *
 * @author Dev
 */
class PasswordValidator extends AbstractValidator
{

    const NOTMATCH = 'notmatch';
    const PASSWORDISEMPTY = 'isempty';
    const PASSWORDVERIFYREQUIRED = 'verifyrequired';

    protected $messageTemplates = array(
        self::NOTMATCH => "Repeated password don't match GUGUS",
        self::PASSWORDISEMPTY => "You have to fill password field GUGUS",
        self::PASSWORDVERIFYREQUIRED => "Verify is required"
    );

    public function isValid($value, $context = null)
    {

        $value = (string) $value;

        if (isset($context['password']) && strlen($context['passwordVerify']) == 0)
        {
            $this->error(self::PASSWORDVERIFYREQUIRED);
            return false;
        }

        return true;
    }

}
