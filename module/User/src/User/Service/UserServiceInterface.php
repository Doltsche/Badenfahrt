<?php

namespace User\Service;

/**
 *
 * @author Dev
 */
interface UserServiceInterface
{
    public function findAll();
    
    public function activate($identity);
    
    public function getByIdentity($identity, $password);
    
    public function save($user);
    
    public function remove($user);
}
