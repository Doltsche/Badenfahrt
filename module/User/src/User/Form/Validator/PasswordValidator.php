<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User\Form\Validator;

/**
 * Description of PasswordValidator
 *
 * @author Dev
 */
class PasswordValidator extends \Zend\Validator\AbstractValidator
{

    const NOTMATCH = 'notmatch';
    const PASSWORDISEMPTY = 'isempty';

    protected $_messageTemplates = array(
        self::NOTMATCH => "Repeated password don't match GUGUS",
        self::PASSWORDISEMPTY => "You have to fill password field GUGUS"
    );

    public function isValid($value, $context = null)
    {

        $value = (string) $value;

        if (isset($context['password']) && !$value)
        {
            $this->_error(self::PASSWORDISEMPTY);
            return false;
        }

        return true;
    }

}
