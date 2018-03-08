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

    /**
     * @access protected $tcp_setting
     * tcp服务器的配置
     * */
    protected $tcp_setting = [
        'max_conn' => 1000, // 设置Server最多允许维持多少个tcp连接，超过此数量时，将会拒绝后面的连接
        'max_request' => 0, // 表示worker进程处理完n次请求后结束运行，manger将会重新创建一个worker进程，此选项为了防止worker进程溢出
        'daemonize' => 1, // 守护进程化
        'reactor_num' => 2, // 线程数，用于调节poll线程数量，以充分利用多核
        'worker_num' => 4, // 设置启动的worker数量
        'heartbeat_check_interval' => 30, // 每隔30秒检查一次，swoole会轮询所有TCP连接，将超过心跳的连接关闭掉
        'backlog' => 128, // 此参数决定最多同时有多少个待accept连接
        'log_file' => '/data/log/swoole.log', // 指定swoole错误日志文件，在swoole发送异常信息会记录到这个文件，默认会打印到屏幕上
        'open_eof_check' => true, // 打开buffer，为检测数据是否完整，如果不完整swoole会等新的数据到来。直到收到一个完整的请求过来，才会发给worker进程
        'package_eof' => "\r\n\r\n", // 设置EOF
        'open_cpu_affinity' => 1, // 设置CPU亲和设置
        'open_tcp_nodelay' => 1, // 启用tcp_nodelay
        'tcp_defer_accept' => 5, // 此参数设定一个秒数，当客户端连接服务器时，在约定秒数并不会触发accept,直到有数据发送，或者超时时才执行
        'dispath_mode' => 1, // worker进程数据包分配模式，1平均分配，2按FD取模固定分配，3抢占式分配，默认为取模
        'debug_mode' => 1 // debug模式是否启动
    ];
    /**
     * @access protected $ws_setting
     * websocket服务器的配置
     * */
    protected $ws_setting = [
        'max_conn' => 1000, // 设置Server最多允许维持多少个tcp连接，超过此数量时，将会拒绝后面的连接
        'max_request' => 0, // 表示worker进程处理完n次请求后结束运行，manger将会重新创建一个worker进程，此选项为了防止worker进程溢出
        'daemonize' => 1, // 守护进程化
        'reactor_num' => 2, // 线程数，用于调节poll线程数量，以充分利用多核
        'worker_num' => 4, // 设置启动的worker数量
        'heartbeat_check_interval' => 30, // 每隔30秒检查一次，swoole会轮询所有TCP连接，将超过心跳的连接关闭掉
        'backlog' => 128, // 此参数决定最多同时有多少个待accept连接
        'log_file' => '/data/log/swoole.log', // 指定swoole错误日志文件，在swoole发送异常信息会记录到这个文件，默认会打印到屏幕上
        'open_eof_check' => true, // 打开buffer，为检测数据是否完整，如果不完整swoole会等新的数据到来。直到收到一个完整的请求过来，才会发给worker进程
        'package_eof' => "\r\n\r\n", // 设置EOF
        'open_cpu_affinity' => 1, // 设置CPU亲和设置
        'open_tcp_nodelay' => 1, // 启用tcp_nodelay
        'tcp_defer_accept' => 5, // 此参数设定一个秒数，当客户端连接服务器时，在约定秒数并不会触发accept,直到有数据发送，或者超时时才执行
        'dispath_mode' => 1, // worker进程数据包分配模式，1平均分配，2按FD取模固定分配，3抢占式分配，默认为取模
        'debug_mode' => 1 ,// debug模式是否启动
        'websocket_subprotocol' => 'chat', // 设置握手响应后Http头会增加'Sec-WebSocket-Protocol: {$websocket_subprotocol}'
    ];

    public function fire($argv)
    {
        if(!extension_loaded('swoole')){
            $this->line("你未使用 Linux 或 Mac Osx 系统，或者并未安装 Swoole 扩展，请进行安装！");
            $this->line("安装教程：");
            $this->line("https://wiki.swoole.com/wiki/page/6.html");
            exit();
        }

            $command = $argv[0];
            switch ($command)
            {
                case 'tcpStart':
                            $this->tcpStart();
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

    /**
     * @access protected tcp服务器启动
     * */
    protected function tcpStart()
    {
        $this->line("swoole tcp服务已经启动");
        $tcp = new \swoole_server("0.0.0.0", 9500);
        $tcp->on('connect', function (\swoole_server $server ,$fd){

        });
        $tcp->on('receive', function (\swoole_server $server, $fd, $from_id, $data){

        });
        $tcp->on('close', function (\swoole_server $server, $fd){

        });
        $tcp->set($this->tcp_setting);
        $tcp->start();
    }

    /**
     * @access protected udp服务器启动
     * */
    protected function udpStart()
    {
        $this->line("swoole udp服务已经启动");
        $serv = new \swoole_server("0.0.0.0", 9500, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
        $serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode' => 1
        ]);
        $serv->on('Packet', function (\swoole_http_server $serv, $data, $clientInfo){
            $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server " . $data);
        });
        $serv->start();
    }

    /**
     * @access protected http服务启动
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

        $websocket->set($this->ws_setting);

        $websocket->start();
    }

    /**
     * @access protected 显示帮助信息
     * */
    protected function help()
    {
        $this->line("php apcmd swoole start | swoole TCP 服务器启动");
        $this->line("php apcmd swoole httpStart | swoole WEB 服务器启动");
        $this->line("php apcmd swoole websocket | swoole websocket 服务器启动");
    }
}
