<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/15/2018
 * Time: 21:31
 */

namespace apphp\Core;


abstract class Middleware
{
    abstract public function handle(\Closure $next);
}