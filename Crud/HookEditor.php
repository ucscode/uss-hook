<?php

namespace Module\Hook\Crud;

use Module\Dashboard\Bundle\Crud\Component\CrudEnum;
use Module\Dashboard\Bundle\Crud\Service\Editor\CrudEditor;
use Module\Hook\Database;
use Ucscode\UssForm\Field\Field;
use Uss\Component\Kernel\Uss;

class HookEditor extends CrudEditor
{
    public function __construct()
    {
        parent::__construct(Database::TABLE_HOOK);
        $this->configure();

        /**
         * @var \Module\Dashboard\Bundle\Crud\Service\Editor\Compact\CrudEditorForm
         */
        $form = $this->getForm()->handleSubmission();
        
        if($form->isSubmitted() && $form->isPersisted()) {
            $base = Uss::instance()->replaceUrlQuery();
            if($this->getChannel() === CrudEnum::CREATE) {
                header("location: $base");
                exit;
            }
        }
    }

    protected function configure(): void
    {
        $this->detachField('id');
        $this->buildFields();
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
                'Disabled',
                'Enabled'
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
        
    }
}