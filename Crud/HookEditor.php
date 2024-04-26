<?php

namespace Module\Hook\Crud;

use Module\Dashboard\Bundle\Crud\Component\CrudEnum;
use Module\Dashboard\Bundle\Crud\Service\Editor\CrudEditor;
use Module\Hook\Database;
use Ucscode\UssForm\Field\Field;
use Ucscode\UssForm\Resource\Service\Pedigree\FieldPedigree;
use Uss\Component\Kernel\Uss;

class HookEditor extends CrudEditor
{
    public function __construct()
    {
        parent::__construct(Database::TABLE_HOOK);
        $this->configure();
    }

    protected function configure(): void
    {
        $this->customSubmissionControl();
        $this->detachField('id');   
        $this->buildFields();
    }

    protected function customSubmissionControl(): void
    {
        $form = $this->getForm();
        
        if($form->isSubmitted()) {
            
            $_POST['area'] = json_encode($_POST['area']);
            
            $form->handleSubmission();

            if($form->isPersisted()) {
                $base = Uss::instance()->replaceUrlQuery();
                if($this->getChannel() === CrudEnum::CREATE) {
                    header("location: $base");
                    exit;
                }
            }
        }
    }

    protected function buildFields(): void
    {
        $this->configureField('position', [
            'nodeName' => Field::NODE_SELECT,
            'options' => [
                'header' => 'Header',
                'footer' => 'Footer'
            ]
        ]);
        
        $this->configureField('status', [
            'nodeName' => Field::NODE_SELECT,
            'options' => [
                'disabled' => 'Disabled',
                'enabled' => 'Enabled'
            ]
        ]);

        $this->configureField('content', [
            'nodeName' => Field::NODE_TEXTAREA,
            'attributes' => [
                'rows' => 20,
                'placeholder' => 'Paste your code here',
                'id' => 'textbox'
            ]
        ]);

        $pedigree = $this->configureField('area', [
            'nodeName' => Field::NODE_SELECT,
            'attributes' => [
                'multiple' => '',
                'size' => 3,
                'class' => 'form-select form-select-lg',
                'name' => 'area[]',
            ],
            'options' => [
                'admin' => 'Admin',
                'user' => 'User',
                'auth' => 'Authentication',
                'others' => 'Others'
            ]
        ]);

        $this->updateAreaField($pedigree);

        $this->configureField('sort', [
            'nodeType' => Field::TYPE_NUMBER,
        ]);
        
    }

    protected function updateAreaField(FieldPedigree $pedigree): void
    {
        if($this->getChannel() === CrudEnum::UPDATE) {
            $properties = $this->getEntity()->getAll();
            $area = json_decode($properties['area'] ?? '[]');
            if(!empty($area)) {
                foreach($pedigree->widget->getOptions() as $key => $value) {
                    if(in_array($key, $area)) {
                        $element = $pedigree->widget->getOptionElement($key);
                        $element->setAttribute('selected', 'selected');
                    }
                }
            }
        }
    }
}