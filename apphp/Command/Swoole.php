<?php

namespace apphp\Command;

use apphp\Core\Command;
use apphp\Init;

class Swoole extends Command
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
        }
    }

    protected function start()
    {
        $this->line("swoole 服务已经启动");
        $serv = new \swoole_server("0.0.0.0", 9501);
        $serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode' => 1
        ]);
    }

    /**
     * */
    protected function httpStart()
    {
        $app = Init::initAll();

        $this->line("swoole https服务已经运行在 <http://127.0.0.1:9502>");
        $http = new \swoole_http_server("127.0.0.1", 9502);
        $http->on('request', function (\swoole_http_request $request, \swoole_http_response $response) use($app){
            $app->exec();
            $response->end( '<h1>Hello World</h1>');
        });
        $http->start();
    }
}
