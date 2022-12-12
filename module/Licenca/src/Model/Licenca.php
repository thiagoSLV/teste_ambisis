<?php

namespace Licenca\Model;

use DomainException;
use Empresa\Controller\EmpresaController;
use Empresa\Model\Empresa;
use Empresa\Model\EmpresaTable;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class Licenca
{
    public $id;
    public $empresa_id;
    public $numero;
    public $orgao_ambiental;
    public $emissao;
    public $validade;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->empresa_id  = !empty($data['empresa_id']) ? $data['empresa_id'] : null;
        $this->numero  = !empty($data['numero']) ? $data['numero'] : null;
        $this->orgao_ambiental  = !empty($data['orgao_ambiental']) ? $data['orgao_ambiental'] : null;
        $this->emissao  = !empty($data['emissao']) ? $data['emissao'] : null;
        $this->validade  = !empty($data['validade']) ? $data['validade'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'empresa_id' => $this->empresa_id,
            'numero' => $this->numero,
            'orgao_ambiental' => $this->orgao_ambiental,
            'emissao' => $this->emissao,
            'validade' => $this->validade,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}