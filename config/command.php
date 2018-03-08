<?php
    $command_app = [
        'config' => \apphp\Command\Config::class,
        'command' => \apphp\Command\Command::class,
        'controller' => \apphp\Command\Controller::class,
        'help' => \apphp\Command\Help::class,
        'model' => \apphp\Command\Model::class,
        'module' => \apphp\Command\Module::class,
        'middleware' => \apphp\Command\Middleware::class,
        'server' =>  \apphp\Command\Server::class,
        'swoole' => \apphp\Command\Swoole::class,
    ];