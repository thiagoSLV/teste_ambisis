<?php 
namespace Licenca\Controller;

use Empresa\Model\EmpresaTable;
use Licenca\Model\LicencaTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Licenca\Form\LicencaForm;
use Licenca\Model\Licenca;
use Laminas\Filter\StringTrim;
use Laminas\Filter\ToInt;
use Laminas\I18n\Filter\Alnum;
use Laminas\I18n\Filter\Alpha;
use Laminas\I18n\Filter\NumberFormat;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class LicencaController extends AbstractActionController
{
    private $licencaTable;
    private $empresaTable;

    public function __construct(LicencaTable $licencaTable, EmpresaTable $empresaTable)
    {
        $this->licencaTable = $licencaTable;
        $this->empresaTable = $empresaTable;
    }

    public function indexAction()
    {
        $aux = [];
        foreach($this->licencaTable->fetchAll() as $result){

            $result->empresa = $this->empresaTable->getEmpresa($result->empresa_id);
            $aux[] = $result;
            
        }
        return new ViewModel([
            'licencas' => $aux,
        ]);
    }

    public function addAction()
    {
        
        $form = new LicencaForm();
        $aux = [];
        foreach($this->empresaTable->fetchAll() as $empresa)
            $aux[$empresa->id] = $empresa->razao_social;
        $form->get('empresa_id')->setValueOptions( count($aux) > 0 
            ? $aux : 
            ['' => 'Não há empresas cadastradas']
        );
        $form->get('submit')->setValue('Cadastrar');

        $request = $this->getRequest();
        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $licenca = new Licenca();
        $form->setInputFilter($this->filters());
        $form->setData($request->getPost());


        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $licenca->exchangeArray($form->getData());
        $this->licencaTable->saveLicenca($licenca);
        return $this->redirect()->toRoute('licenca');
    }

    public function editAction()
    {
        
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('licenca', ['action' => 'add']);
        }

        try {
            $licenca = $this->licencaTable->getLicenca($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('licenca', ['action' => 'index']);
        }

        $form = new LicencaForm();
        $form = new LicencaForm();
        $aux = [];
        foreach($this->empresaTable->fetchAll() as $empresa)
            $aux[$empresa->id] = $empresa->razao_social;
            $form->get('empresa_id')->setValueOptions( count($aux) > 0 
                ? $aux : 
                ['' => 'Não há empresas cadastradas']
            );
        $form->bind($licenca);
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
            $this->licencaTable->saveLicenca($licenca);
        } catch (\Exception $e) {
        }

        // Redirect to licenca list
        return $this->redirect()->toRoute('licenca', ['action' => 'index']);
    }

    public function deleteAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('licenca');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->licencaTable->deleteLicenca($id);
            }

            return $this->redirect()->toRoute('licenca');
        }

        return [
            'id'    => $id,
            'licenca' => $this->licencaTable->getLicenca($id),
        ];
    }
    private function filters($request = NULL){
        $notEmptyValidator = [
            'name' => NotEmpty::class,
            'options' => [
                'messages' => [NotEmpty::IS_EMPTY => 'O campo deve ser preenchido']
            ]
        ];
        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
        if($request != 'update'){
            $inputFilter->add([
                'name' => 'numero',
                'required' => true,
                'continue_if_empty' => false,
                'filters' => [
                    ['name' => Alnum::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [$notEmptyValidator],
            ]);
        }
        $inputFilter->add([
            'name' => 'orgao_ambiental',
            'required' => true,
            'continue_if_empty' => false,
            'filters' => [
                ['name' => Alpha::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [$notEmptyValidator],
        ]);
        $inputFilter->add([
            'name' => 'empresa_id',
            'required' => true,
            'continue_if_empty' => false,
            'filters' => [
                ['name' => Alnum::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [$notEmptyValidator],
        ]);
        $inputFilter->add([
            'name' => 'validade',
            'required' => true,
            'continue_if_empty' => false,
            'validators' => [$notEmptyValidator],
        ]);
        return $inputFilter;
    }
}