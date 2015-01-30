<?php

return array(
    'doctrine' => array(
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => array(__DIR__ . '/xml/zfcuser', __DIR__ . '/xml/badenfahrt',),
            ),
 
            'orm_default' => array(
                'drivers' => array(
					'ZfcUser\Entity'  => 'zfcuser_entity',
                    'Badenfahrt\Entity' => 'zfcuser_entity',
                ),
            ),
        ),
    ),
 
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'Badenfahrt\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),
 
    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
 
        'role_providers' => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'Badenfahrt\Entity\Role',
            ),
        ),
		'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
				array('route' => 'home', 'roles' => array('guest', 'administrator')),
			),
        ),
    ),
);