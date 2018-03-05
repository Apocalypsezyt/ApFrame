<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:27
 */

namespace apphp;


class loadStyle
{
    public static function loadCss(array $css_array)
    {
        if(USE_STYLE)
        {
            foreach ($css_array as $css)
            {
                try
                {
                    echo "<link rel='stylesheet' type='text/css' href=" . CSS_PATH . $css . ">";
                }
                catch (\Exception $e)
                {
                    return false;
                }
            }
        }
    }

    public static function loadScript(array $js_array)
    {
        if(USE_STYLE)
        {
            foreach ($js_array as $js)
            {
                echo "<script src='/resource/js/"."$js'>";
            }
            foreach ($js_array as $js)
            {
                try
                {
                    echo $js;
                    echo  "<script type=\"text/javascript\" src=\"". JS_PATH . $js. "\">";
                }
                catch (\Exception $e)
                {
                    return false;
                }
            }
        }
    }
}