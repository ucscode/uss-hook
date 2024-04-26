<?php

namespace Module\Hook\Crud;

use Module\Dashboard\Bundle\Crud\Service\Inventory\CrudInventory;
use Module\Hook\Database;
use Ucscode\DOMTable\Interface\DOMTableIteratorInterface;

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

        $this->addEntityMutationIterator('hook', $this->entityIterator());
    }

    protected function entityIterator(): DOMTableIteratorInterface
    {
        return new class implements DOMTableIteratorInterface {
            public function foreachItem(array $item): ?array
            {
                return $item;
            }
        };
    }
}