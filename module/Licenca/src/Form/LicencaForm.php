<?php

namespace Licenca\Form;

use Laminas\Form\Form;

class LicencaForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('licenca');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'numero',
            'type' => 'text',
            'options' => [
                'label' => 'Número',
            ],
        ]);
        $this->add([
            'name' => 'orgao_ambiental',
            'type' => 'text',
            'options' => [
                'label' => 'Orgão ambiental',
            ],
        ]);

        $this->add([
            'name' => 'empresa_id',
            'type' => 'select',
            'options' => [
                'label' => 'Empresa',
                'value_options' => []
            ],
        ]);

        $this->add([
            'name' => 'validade',
            'type' => 'date',
            'options' => [
                'label' => 'Validade',
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