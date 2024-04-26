<?php 

namespace Module\Hook;

use Uss\Component\Kernel\Uss;

new class {

    public function __construct()
    {
        Uss::instance()->filesystemLoader->addPath(__DIR__ . '/templates', 'Hook');
        new Database();
        new AdminControl();
        new PlacementControl();
    }

};