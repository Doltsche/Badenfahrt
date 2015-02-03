<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User\Mapper;

/**
 *
 * @author Dev
 */
interface UserMapperInterface
{
    public function findById($id);
    
    public function findByIdentity($identity);
    
    public function findByDisplayName($displayName);
    
    public function findByToken($token);
    
    public function findAll();
    
    public function save($user);
    
    public function remove($user);
}
