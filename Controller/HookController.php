<?php

namespace Module\Hook\Controller;

use Module\Dashboard\Bundle\Crud\Component\CrudEnum;
use Module\Dashboard\Bundle\Kernel\Abstract\AbstractDashboardController;
use Module\Dashboard\Foundation\Admin\AdminDashboard;
use Module\Hook\Crud\HookEditor;
use Module\Hook\Crud\HookInventory;
use Module\Hook\Database;

class HookController extends AbstractDashboardController
{
    public function onload(array $context): void
    {
        parent::onload($context);
        
        $channel = $_GET['channel'] ?? null;
        
        $isEditor = in_array($channel, [CrudEnum::CREATE->value, CrudEnum::UPDATE->value]);

        $crudObject = $isEditor ? new HookEditor() : new HookInventory();

        AdminDashboard::instance()->getDocument('hook')->getMenuItem(
            $crudObject->getChannel() === CrudEnum::CREATE ? 'hook.new' : 'hook'
        )->setAttribute('active', true);

        $this->document->setContext([
            'element' => $crudObject->build(),
            'isEditor' => $isEditor,
        ]);
    }
}