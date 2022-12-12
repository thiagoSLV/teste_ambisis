<?php 
namespace Licenca;

use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'licenca' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/licenca[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\LicencaController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'licenca' => __DIR__ . '/../view',
        ],
    ],
];