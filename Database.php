<?php

namespace Module\Hook;

use Uss\Component\Kernel\Uss;

class Database 
{
    public const TABLE_HOOK = ENV_DB_PREFIX . 'hook';

    public function __construct()
    {
        $tableList = [
            $this->createTableContext(),
        ];

        foreach($tableList as $SQL) {
            Uss::instance()->mysqli->query($SQL);
        }
    }

    protected function createTableContext(): string 
    {
        return sprintf(
            "CREATE TABLE IF NOT EXISTS %s (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                position VARCHAR(12) NOT NULL DEFAULT 'header',
                area TEXT NOT NULL,
                status TINYINT NOT NULL DEFAULT 1,
                content TEXT
            )",
            self::TABLE_HOOK
        );
    }
}