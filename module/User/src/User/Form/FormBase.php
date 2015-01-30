<?php

namespace User\Form;

use Zend\Form\Form;

/**
 * Description of FormBase
 *
 * @author Dev
 */
class FormBase extends Form
{

    public function __construct()
    {
        parent::__construct();
    }

    public function highlightErrorElements($class, $current = null)
    {
        if (!$current)
        {
            $current = $this;
        }

        foreach ($this->currentElement as $element)
        {
            if ($element->hasErrors())
            {
                $element->setAttrib('class', $class);
            }

            if ($element->getElements())
            {
                $this->highlightErrorElements($class, $element);
            }
        }
    }

}
