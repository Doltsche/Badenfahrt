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
interface PersonalMapperInterface
{
    public function findById($id);
    
    public function findAll();
    
    public function save($personal);
    
    public function remove($personal);
}
