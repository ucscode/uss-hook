<?php

namespace Module\Hook\Crud;

use Module\Dashboard\Bundle\Crud\Service\Inventory\CrudInventory;
use Module\Hook\Database;

class HookInventory extends CrudInventory
{
    public function __construct()
    {
        parent::__construct(Database::TABLE_HOOK);
        $this->configure();
    }

    protected function configure(): void
    {
        $this
            ->setTableBackgroundWhite()
            ->disableGlobalActions()
            ->removeWidget('inventory:global-action')
        ;
        
        $this
            ->removeColumn('id')
            ->removeColumn('content')
        ;
    }
}