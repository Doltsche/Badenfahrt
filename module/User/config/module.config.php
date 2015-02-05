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
                    'register' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'User\Controller\Register',
                                'action' => 'registerUser',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'personal' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/personal',
                                    'defaults' => array(
                                        'controller' => 'User\Controller\Register',
                                        'action' => 'registerPersonal',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'profile' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/profile',
                            'defaults' => array(
                                'action' => 'profile',
                            ),
                        ),
                    ),
                    'confirm' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/confirm/:token',
                            'defaults' => array(
                                'controller' => 'User\Controller\Register',
                                'action' => 'confirm',
                            ),
                            'constraints' => array(
                                'token' => '[a-zA-Z0-9_-]+',
                            ),
                        ),
                    ),
                    'confirmPrompt' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/confirmPrompt',
                            'defaults' => array(
                                'controller' => 'User\Controller\Register',
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
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                    'constraints' => array(
                                        'id' => '[1-9]\d*',
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
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
            'User\Controller\Register' => 'User\Controller\RegisterController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'editUserModal' => __DIR__ . '/../view/user/dialog/edit-user-modal.phtml'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'User\Service\UserMailServiceInterface' => 'User\Service\UserMailService',
        ),
        'factories' => array(
            'User\Mapper\UserMapperInterface' => 'User\Mapper\Factory\UserMapperFactory',
            'User\Mapper\RoleMapperInterface' => 'User\Mapper\Factory\RoleMapperFactory',
            'User\Mapper\PersonalMapperInterface' => 'User\Mapper\Factory\PersonalMapperFactory',
            'User\Authentication\IdentityProvider' => 'User\Factory\IdentityProviderFactory',
            'User\Service\UserPasswordServiceInterface' => 'User\Service\Factory\UserPasswordServiceFactory',
            'user_authentication_service' => 'User\Authentication\Factory\AuthenticationServiceFactory',
            'register_user_form' => 'User\Form\Factory\RegisterUserFormFactory',
            'edit_user_form' => 'User\Form\Factory\EditUserFormFactory',
            'register_personal_form' => 'User\Form\Factory\RegisterPersonalFormFactory',
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
        'authenticated_role' => 'registered',
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
                'guest' => array(),
                'registered' => array(),
                'user' => array(),
                'administrator' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('guest'), 'guest'),
                    array(array('registered'), 'registered'),
                    array(array('user'), 'user'),
                    array(array('administrator'), 'administrator'),
                ),
            ),
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'Application\Controller\Application', array('action' => 'index'), 'roles' => array('guest', 'user', 'administrator')),
                array('controller' => 'User\Controller\User', 'action' => 'login', 'roles' => array('guest')),
                array('controller' => 'User\Controller\User', 'action' => 'profile', 'roles' => array('registered', 'user')), // TODO: remove registered
                array('controller' => 'User\Controller\User', 'action' => 'test', 'roles' => array('guest', 'user')),
                array('controller' => 'User\Controller\User', 'action' => 'logout', 'roles' => array('registered', 'user', 'administrator')),
                array('controller' => 'User\Controller\Register', 'action' => 'registerUser', 'roles' => array('guest')),
                array('controller' => 'User\Controller\Register', 'action' => 'registerPersonal', 'roles' => array('registered')),
                array('controller' => 'User\Controller\Register', 'action' => 'confirm', 'roles' => array('guest', 'registered')),
                array('controller' => 'User\Controller\User', 'action' => 'edit', 'roles' => array('user', 'administrator')),
                array('controller' => 'User\Controller\User', 'action' => 'manage', 'roles' => array('user', 'administrator')),
            ),
        ),
    ),
);
