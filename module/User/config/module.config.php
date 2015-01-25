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
        'factories' => array()
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
