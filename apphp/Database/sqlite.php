<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:57
 */

namespace apphp\database;


class sqlite implements Database
{
    private $sqlite;

    function __construct()
    {
        $this->sqlite = new \SQLite3(SQLITE_FILE);
    }

    public function query($sql)
    {
        $this->sqlite->exec($sql);
    }

    public function exec($sql)
    {
        // TODO: Implement exec() method.
    }

    public function close()
    {
        $this->sqlite->close();
    }
}