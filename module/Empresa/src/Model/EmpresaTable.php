<?php

namespace Empresa\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class EmpresaTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getEmpresa($id)
    {
        $id = (int) $id;
        if ($id == 0)
            return [];
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveEmpresa(Empresa $empresa)
    {
        $data = [
            'razao_social' => $empresa->razao_social,
            'cnpj' => $empresa->cnpj,
            'cep' => $empresa->cep,
            'estado' => $empresa->estado,
            'cidade' => $empresa->cidade,
            'bairro' => $empresa->bairro,
            'complemento' => $empresa->complemento,
        ];

        $id = (int) $empresa->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getEmpresa($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update empresa with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteEmpresa($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}