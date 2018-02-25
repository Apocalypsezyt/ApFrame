<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/15/2018
 * Time: 20:43
 */

namespace app\test\Middleware;


use apphp\Core\Middleware;

class CheckTest extends Middleware
{
    public function handle(\Closure $next)
    {
        if(true){
            header('Location:/');
        }

        return $next();
    }
}