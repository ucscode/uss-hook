<?php

namespace Module\Hook;

use Module\Dashboard\Bundle\User\User;
use Module\Dashboard\Foundation\Admin\AdminDashboard;
use Module\Dashboard\Foundation\User\UserDashboard;
use Ucscode\SQuery\Condition;
use Ucscode\SQuery\SQuery;
use Uss\Component\Block\BlockManager;
use Uss\Component\Event\Event;
use Uss\Component\Kernel\Uss;
use Uss\Component\Route\Route;

class PlacementControl
{
    protected User $user;
    protected array $hooks;
    protected string $adminBase;
    protected string $userBase;

    public function __construct()
    {
        $this->user = (new User())->acquireFromSession();
        $this->hooks = $this->getEnabledHooks();
        $this->adminBase = AdminDashboard::instance()->appControl->getUrlBasePath();
        $this->userBase = UserDashboard::instance()->appControl->getUrlBasePath();
        $this->placeSourceCodes();
    }

    protected function getEnabledHooks(): array
    {
        $squery = (new SQuery())
            ->select()
            ->from(Database::TABLE_HOOK)
            ->where(
                (new Condition())
                    ->add('status', 'enabled')
            )
            ->orderBy('sort')
        ;

        $result = Uss::instance()->mysqli->query($squery->build());

        return array_map(function($data) {
            $data['area'] = json_decode($data['area'], true);
            return $data;
        }, $result->fetch_all(MYSQLI_ASSOC));
    }

    protected function isArea(string $position): bool
    {
        $urlBase = Uss::instance()->getUrlSegments(0);

        return match($position) {
            'admin' => $this->adminBase === ($urlBase),
            'auth' => $this->isAreaAuth($urlBase),
            'user' => $this->userBase === ($urlBase) && !$this->isAreaAuth($urlBase),
            'others' => !in_array($urlBase, [$this->adminBase, $this->userBase]),
            default => false
        };
    }

    protected function placeSourceCodes(): void
    {
        Event::instance()->addListener('onload:before', function(Route $route) {
            foreach($this->hooks as $hook) {
                foreach($hook['area'] as $area) {
                    if($this->isArea($area)) {
                        $blockName = ($hook['position'] == 'header') ? 'head_javascript' : 'body_javascript';
                        $block = BlockManager::instance()->getBlock($blockName);
                        $block->addContent(sprintf("hook-%s", $hook['id']), $hook['content']);
                    }
                }
            }
        }, 100);
    }

    private function isAreaAuth(?string $base): bool
    {
        if(in_array($base, [$this->userBase, $this->adminBase])) {
            if($this->user->isAvailable()) {
                return in_array(Uss::instance()->getUrlSegments(1), [
                    'register',
                    'reset-password'
                ]);
            }
            return true;
        }
        return false;
    }
}