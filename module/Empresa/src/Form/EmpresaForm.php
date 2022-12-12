<?php

namespace Empresa\Form;

use Empresa\Form\Estado;
use Laminas\Form\Form;

class EmpresaForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('empresa');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'razao_social',
            'type' => 'text',
            'options' => [
                'label' => 'Razão Social*',
            ],
        ]);
        $this->add([
            'name' => 'cnpj',
            'type' => 'text',
            'options' => [
                'label' => 'CNPJ*',
            ],
        ]);
        $this->add([
            'name' => 'cep',
            'type' => 'text',
            'options' => [
                'label' => 'CEP*',
            ],
        ]);
        $this->add([
            'name' => 'cidade',
            'type' => 'text',
            'options' => [
                'label' => 'Cidade*',
            ],
        ]);

        $this->add([
            'name' => 'estado',
            'type' => 'select',
            'options' => [
                'label' => 'Estado*',
                'value_options' => 
                    Estado::toArray()
            ],
        ]);

        $this->add([
            'name' => 'bairro',
            'type' => 'text',
            'options' => [
                'label' => 'Bairro*',
            'mask' => '**.**',

            ],
        ]);

        $this->add([
            'name' => 'complemento',
            'type' => 'text',
            'options' => [
                'label' => 'Complemento',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
        
    }
}