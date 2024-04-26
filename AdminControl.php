<?php

namespace Module\Hook;

use Module\Dashboard\Bundle\Crud\Component\CrudEnum;
use Module\Dashboard\Bundle\Document\Document;
use Module\Dashboard\Foundation\Admin\AdminDashboard;
use Module\Hook\Controller\HookController;
use Uss\Component\Kernel\Uss;

class AdminControl {

    protected AdminDashboard $adminDashboard;
    protected string $namespace;

    public function __construct()
    {
        $this->adminDashboard = AdminDashboard::instance();
        $this->createHookDocument();
    }

    protected function createHookDocument(): void
    {
        $document = (new Document())
            ->setRoute('/hook', $this->adminDashboard->appControl->getUrlBasePath())
            ->setTemplate('/main.html.twig', '@Hook')
            ->setController(new HookController())
        ;

        $document->addMenuItem('hook', [
            'label' => 'Hooks',
            'icon' => 'bi bi-plugin',
            'href' => $document->getUrl(),
            'order' => 3.5,
            'auto-focus' => false,
        ], $this->adminDashboard->menu);

        $document->addMenuItem('hook.new', [
            'label' => 'Add new',
            'href' => Uss::instance()->replaceUrlQuery([
                'channel' => CrudEnum::CREATE->value,
            ], $document->getUrl()),
            'auto-focus' => false,
        ], $document->getMenuItem('hook'));
        
        $this->adminDashboard->addDocument('hook', $document);
    }
}