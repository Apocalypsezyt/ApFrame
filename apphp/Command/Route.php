<?php

namespace apphp\Command;

use apphp\Core\Command;

class Route extends Command
{
    public function fire($argv)
    {
        $command = $argv[0];
        switch ($command)
        {
            case 'start':
                $this->start();
                break;
            case 'httpStart':
                $this->httpStart();
                break;
            case 'websocket':
                $this->websocketStart();
                break;
            case 'help':
            default:
                $this->help();
        }
    }
}
