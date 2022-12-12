<?php

namespace Empresa\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class Empresa
{
    public $id;
    public $razao_social;
    public $cnpj;
    public $cep;
    public $cidade;
    public $estado;
    public $bairro;
    public $complemento;
    private $inputFilter;
    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->razao_social = !empty($data['razao_social']) ? $data['razao_social'] : null;
        $this->cnpj  = !empty($data['cnpj']) ? $data['cnpj'] : null;
        $this->cep  = !empty($data['cep']) ? $data['cep'] : null;
        $this->cidade  = !empty($data['cidade']) ? $data['cidade'] : null;
        $this->estado  = !empty($data['estado']) ? $data['estado'] : null;
        $this->bairro  = !empty($data['bairro']) ? $data['bairro'] : null;
        $this->complemento  = !empty($data['complemento']) ? $data['complemento'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'razao_social' => $this->razao_social,
            'cnpj' => $this->cnpj,
            'cep' => $this->cep,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'bairro' => $this->bairro,
            'complemento' => $this->complemento,
        ];
    }
}