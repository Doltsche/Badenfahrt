<?php

return array(
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            'user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'login',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'confirm' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/confirm/:token',
                            'defaults' => array(
                                'action' => 'confirm',
                            ),
                        ),
                    ),
                    'confirm' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/confirmPrompt',
                            'defaults' => array(
                                'action' => 'confirmPrompt',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete/:id',
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                            'constraints' => array(
                                'id' => '[1-9]\d*',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/edit',
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'manage' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/manage',
                            'defaults' => array(
                                'action' => 'manage',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'action' => 'register',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
           'User\Service\UserMailServiceInterface' => 'User\Service\UserMailService',
        ),
        'factories' => array(
            'User\Mapper\UserMapperInterface' => 'User\Mapper\Factory\UserMapperFactory',
            'User\Mapper\RoleMapperInterface' => 'User\Mapper\Factory\RoleMapperFactory',
            'User\Authentication\IdentityProvider' => 'User\Factory\IdentityProviderFactory',
            'user_authentication_service' => 'User\Authentication\Factory\AuthenticationServiceFactory',
            'user_register_form' => 'User\Form\Factory\RegisterFormFactory',
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'loginWidget' => 'User\ViewHelper\Factory\LoginWidgetFactory',
            'userIdentity' => 'User\ViewHelper\Factory\UserIdentityFactory',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'user_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/User/Model',),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Model' => 'user_entity',
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',
        // default role for authenticated users
        'authenticated_role'    => 'registered',
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'User\Authentication\IdentityProvider',
        'role_providers' => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'User\Model\Role',
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                '\User\Model\User' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('administrator'), '\User\Model\User', array('edit', 'manage')),
                    array(array('user'), '\User\Model\User', 'edit'),
                ),
            ),
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'home', 'roles' => array('guest', 'user', 'administrator')),
                array('route' => 'user', 'roles' => array('guest')),
                array('route' => 'user/logout', 'roles' => array('registered', 'user', 'administrator')),
                array('route' => 'user/register', 'roles' => array('guest')),
                array('route' => 'user/confirm', 'roles' => array('guest', 'registered',)),
                array('route' => 'user/edit', 'roles' => array('user', 'administrator')),
                array('route' => 'user/manage', 'roles' => array('administrator')),
            ),
        ),
    ),
);
