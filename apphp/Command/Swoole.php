<?php

namespace apphp\Command;

use apphp\Core\Command;
use apphp\Init;

/**
 * 该命令行使用了swoole扩展
 * 必须在 Linux/Mac OSX 上运行
 * 详情请见 swoole.so 扩展如何安装
 * */

class Swoole extends Command
{
    public function fire($argv)
    {
        if(!extension_loaded('swoole')){
            $this->line("未安装swoole扩展，请进行安装！");
            exit();
        }

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
            default:
                    $this->help();
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
        $serv->start();
    }

    /**
     * */
    protected function httpStart()
    {
        $app = Init::initAll();

        $this->line("swoole https服务已经运行在 <http://127.0.0.1:9502>");
        $http = new \swoole_http_server("127.0.0.1", 9502);
        $http->on('request', function (\swoole_http_request $request, \swoole_http_response $response) use($app){
            $info = $app->exec();
            $response->end( '<h1>Hello World</h1>');
        });

        $http->start();
    }

    /**
     * @access protected 启动 websocket 服务器
     * */
    protected function websocketStart()
    {

        $this->line("swoole websocket服务已经运行在 <ws://127.0.0.1:2000>");

        $websocket = new \swoole_websocket_server("127.0.0.1", 2000);

        $websocket->on('open', function (\swoole_websocket_server $server, $request){
            $this->line("已经有朋友连上我们websocket服务器了");
        });

        $websocket->on('message', function (\swoole_websocket_server $server, $frame){
            $this->line("有朋友发来了数据");
        });

        $websocket->on('close', function ($ser, $fd){
            $this->line("client ${fd} 已断开了连接");
        });

        $websocket->start();
    }

    /**
     * @access protected 显示帮助信息
     * */
    protected function help()
    {
        $this->line("php apcmd swoole start | swoole TCP 服务器启动");
        $this->line("php apcmd swoole httpStart | swoole WEB 服务器启动");
        $this->line("php apcmd swoole start | swoole TCP 服务器启动");
    }
}
