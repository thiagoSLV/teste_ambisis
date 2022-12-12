<?php 
namespace Licenca;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\LicencaTable::class => function($container) {
                    $tableGateway = $container->get(Model\LicencaTableGateway::class);
                    return new Model\LicencaTable($tableGateway);
                },
                Model\LicencaTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Licenca());
                    return new TableGateway('licenca', $dbAdapter, null, $resultSetPrototype);
                },
                Model\EmpresaTable::class => function($container) {
                    $tableGateway = $container->get(Model\EmpresaTableGateway::class);
                    return new \Empresa\Model\EmpresaTable($tableGateway);
                },
                Model\EmpresaTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Empresa\Model\Empresa());
                    return new TableGateway('empresa', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\LicencaController::class => function($container) {
                    return new Controller\LicencaController(
                        $container->get(Model\LicencaTable::class), $container->get(Model\EmpresaTable::class)
                    );
                },
                Controller\EmpresaController::class => function($container) {
                    return new Controller\EmpresaController(
                        $container->get(Model\EmpresaTable::class)
                    );
                },
            ],
        ];
    }
}