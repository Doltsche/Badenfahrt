<?php

namespace User\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\EditUserForm;
use User\Form\Filter\EditUserFilter;

/**
 * The factory creates an instance of the EditUserForm class.
 */
class EditUserFormFactory implements FactoryInterface
{

    /**
     * Creates an instance of the EditUserForm class.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return EditUserForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('User\Mapper\UserMapperInterface');
       
        $filter = new EditUserFilter($userMapper);

        $form = new EditUserForm();
        $form->setInputFilter($filter);

        $form->setAttribute('id', 'editUserForm');
        
        return $form;
    }

}