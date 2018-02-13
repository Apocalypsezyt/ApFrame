<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/11/2018
 * Time: 15:41
 */

namespace apphp\Core\Cache;


trait Config
{
    /**
     * 缓存配置成一个数组并存储到 bootstrap/config 目录下
     *
     * @return bool
     * */
    public function cache() : bool
    {
        $config = $this->read();
        $config_file_name = md5('.set') . '.php';
        $config_file_content = "<?php return " . var_export($config, true) . ';';

        return file_put_contents(BOOT_PATH . 'config/' . $config_file_name, $config_file_content) ? true : false;
    }

    /**
     * 读取配置文件到一个数组
     *
     * @return array 一个数组的配置文件
     * */
    public function read() : array
    {
        $config = array();

        // 判断是否存在缓存文件 存在就读取
        $set_cache_file = BOOT_PATH . 'config/' . md5('.set') . '.php';
        if(file_exists($set_cache_file)){
            $config = require $set_cache_file;
            return $config;
        }

        $fp = fopen(ROOT_PATH . '/.set', 'r');
        while(!feof($fp)){
            $line = fgets($fp);
            // 读取 .set文件 $matches[0]是一行 $matches[1]是key $matches[2]是value &是改变外部变量
            preg_replace_callback('/([A-Za-z0-9_.]+)=([A-Za-z0-9_.]+)/',function ($matches) use (&$config){
                if($matches[2] === 'true')
                    $config[$matches[1]] = true;
                elseif($matches[2] === 'false')
                    $config[$matches[1]] = false;
                else
                    $config[$matches[1]] = $matches[2];

            }, $line);
        }

        return $config;
    }

    /**
     * 清除掉 bootstrap/config 目录下的配置文件
     *
     * @return bool
     * */
    public function destroy()
    {
        $config_file_name = md5('.set') . '.php';

        return unlink(BOOT_PATH . 'config/' . $config_file_name);
    }
}