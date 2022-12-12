<?php 
namespace Empresa;

use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'empresa' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/empresa[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\EmpresaController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'empresa' => __DIR__ . '/../view',
        ],
    ],
];