<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/1
 * Time: 21:27
 */

namespace apphp\Facilitate\Format;


class Json
{
    public static function encode(array $info)
    {
        return json_encode($info);
    }

    public static function echoEncode(array $info)
    {
        echo self::encode($info);
    }
}