<?php 
namespace Empresa\Controller;

use Empresa\Model\EmpresaTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Empresa\Form\EmpresaForm;
use Empresa\Model\Empresa;
use Laminas\Filter\StringTrim;
use Laminas\Filter\ToInt;
use Laminas\I18n\Filter\Alnum;
use Laminas\I18n\Filter\Alpha;
use Laminas\I18n\Filter\NumberFormat;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Licenca\Model\LicencaTable;

class EmpresaController extends AbstractActionController
{
    private $empresaTable;
    private $licencaTable;


    public function __construct(EmpresaTable $empresaTable, LicencaTable $licencaTable)
    {
        $this->empresaTable = $empresaTable;
        $this->licencaTable = $licencaTable;
    }

    public function indexAction()
    {
        $aux = [];
        foreach($this->empresaTable->fetchAll() as $result){
            $this->licencaTable->getLicencaByEmpresaId($result->id);
            $result->licencas = $this->licencaTable->getLicencaByEmpresaId($result->id);
            $aux[] = $result;
            
        }
        return new ViewModel([
            'empresas' => $aux,
        ]);
    }

    public function addAction()
    {
        
        $form = new EmpresaForm();
        $form->get('submit')->setValue('Cadastrar');

        $request = $this->getRequest();
        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $empresa = new Empresa();
        $form->setInputFilter($this->filters());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $empresa->exchangeArray($form->getData());
        $this->empresaTable->saveEmpresa($empresa);
        return $this->redirect()->toRoute('empresa');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('empresa', ['action' => 'add']);
        }

        try {
            $empresa = $this->empresaTable->getEmpresa($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('empresa', ['action' => 'index']);
        }

        $form = new EmpresaForm();
        $form->bind($empresa);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($this->filters('update'));
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->empresaTable->saveEmpresa($empresa);
        } catch (\Exception $e) {
        }

        // Redirect to empresa list
        return $this->redirect()->toRoute('empresa', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('empresa');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Sim') {
                $empresa = $this->empresaTable->getEmpresa($id);
                $licencas = $this->licencaTable->getLicencaByEmpresaId($empresa->id);
                foreach($licencas as $licenca){
                    $licenca->empresa_id = NULL;
                    $this->licencaTable->saveLicenca($licenca);
        
                }
                
                $id = (int) $request->getPost('id');
                $this->empresaTable->deleteEmpresa($id);
            }

            // Redirect to list of empresas
            return $this->redirect()->toRoute('empresa');
        }

        return [
            'id'    => $id,
            'empresa' => $this->empresaTable->getEmpresa($id),
        ];
    }
    private function filters($request = null){
        
        $inputFilter = new InputFilter();
        $notEmptyValidator = [
            'name' => NotEmpty::class,
            'options' => [
                'messages' => [NotEmpty::IS_EMPTY => 'O campo deve ser preenchido']
            ]
        ];
        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
        $inputFilter->add([
            'name' => 'razao_social',
            'required' => true,
            'continue_if_empty' => false,
            'filters' => [
                ['name' => Alnum::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                $notEmptyValidator
            ],
        ]);
        if($request != "update"){
            $inputFilter->add([
                'name' => 'cnpj',
                'required' => true,
                'continue_if_empty' => false,
                'filters' => [
                    ['name' => Alnum::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'messages' => [StringLength::TOO_SHORT => 'O campo deve conter %min% caracteres'],
                            'min' => 14,
                            'max' => 14,
                        ],
                    ],
                    $notEmptyValidator
                ],
            ]);
        }
        $inputFilter->add([
            'name' => 'cep',
            'required' => true,
            'continue_if_empty' => false,
            'filters' => [
                ['name' => Alnum::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'messages' => [StringLength::TOO_SHORT => 'O campo deve conter %min% caracteres'],
                        'min' => 8,
                        'max' => 8,
                    ],
                ],
                $notEmptyValidator
            ],
        ]);
        $inputFilter->add([
            'name' => 'cidade',
            'required' => true,
            'continue_if_empty' => false,
            'filters' => [
                ['name' => Alpha::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [$notEmptyValidator],
        ]);
        $inputFilter->add([
            'name' => 'bairro',
            'required' => true,
            'continue_if_empty' => false,
            'filters' => [
                ['name' => Alpha::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [$notEmptyValidator],
        ]);
        $inputFilter->add([
            'name' => 'complemento',
            'required' => false,
            'filters' => [
                ['name' => Alpha::class],
                ['name' => StringTrim::class],
            ]
        ]);


        return $inputFilter;
    }
}