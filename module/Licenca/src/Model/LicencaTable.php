<?php

namespace Licenca\Model;

use Carbon\Carbon;
use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class LicencaTable
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

    public function getLicenca($id)
    {
        $id = (int) $id;
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
    public function getLicencaByEmpresaId($id)
    {
        $id = (int) $id;
        if ($id == 0)
            return [];
        return $this->tableGateway->select(['empresa_id' => $id]);
        
    }

    public function saveLicenca(Licenca $licenca)
    {
        $data = [
            'id' => $licenca->id,
            'empresa_id' => $licenca->empresa_id,
            'numero' => $licenca->numero,
            'orgao_ambiental' => $licenca->orgao_ambiental,
            'emissao' => Carbon::now(),
            'validade' => $licenca->validade,
        ];

        $id = (int) $licenca->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getLicenca($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update licenca with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteLicenca($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}