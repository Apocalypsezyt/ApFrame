<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 3/5/2018
 * Time: 17:30
 */

namespace apphp\Core;

/**
 * 该类获取系统信息
 *
 *
 * */

class OperatingSystem
{
    /**
     * @access public 获取php版本信息
     * @return float 返回使用的PHP版本
     * */
    public function phpVersion()
    {
        return phpversion();
    }

    /**
     * @access public 获取Zend版本
     * @return float 返回Zend的虚拟机版本
     * */
    public function ZendVersion()
    {
        return zend_version();
    }

    /**
     * @access public 获取系统信息
     * @return string 返回使用的系统
     * */
    public function getSystemInfo()
    {
        $system_info = $_SERVER['HTTP_USER_AGENT'];

        if(preg_match('/win/i', $system_info) && strpos($system_info, '95')){
            $os = "Windows 95";
        }
        elseif(preg_match('/win 9x/i', $system_info) && strpos($system_info, '4.90')){
            $os = "Windows ME";
        }
        elseif(preg_match('/win/i', $system_info) && preg_match('/98/i', $system_info)){
            $os = "Windows 98";
        }
        elseif(preg_match('/win/i', $system_info) && preg_match('/nt 5.1/i', $system_info)){
            $os = "Windows XP";
        }
        elseif(preg_match('/win/i', $system_info) && preg_match('/nt 6.0/i', $system_info)){
            $os = "Windows Vista";
        }
        elseif(preg_match('/win/i', $system_info) && preg_match('/nt 6.1/i', $system_info)){
            $os = "Windows 7";
        }
        elseif(preg_match('/win/i', $system_info) && preg_match('/nt 6.2/i', $system_info)){
            $os = "Windows 8";
        }
        elseif(preg_match('/win/i', $system_info) && preg_match('/nt 10.0/i', $system_info)){
            $os = "Windows 10";
        }
        elseif(preg_match('/linux/i', $system_info)){
            $os = "Linux";
        }
        elseif(preg_match('/Mac/i', $system_info)){
            $os = "Mac OSX";
        }
        elseif(preg_match('/unix/i', $system_info)){
            $os = "Unix";
        }
        elseif(preg_match('/sun/i', $system_info) && preg_match('/os/i', $system_info)){
            $os = "SunOs";
        }
        else{
            $os = "others";
        }

        return $os;
    }

    /**
     * @access public 获取浏览器信息
     * @return string 返回使用的浏览器
     * */
    public function clientBrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if(preg_match('/msie/i', $agent)){
            $browser = 'MSIE';
        }
        elseif(preg_match('/Firefox/i', $agent)){
            $browser = 'Firefox';
        }
        elseif(preg_match('/Chrome/i', $agent)){
            $browser = 'Chrome';
        }
        elseif(preg_match('/Safari/i', $agent)){
            $browser = 'Safari';
        }
        elseif(preg_match('/Opera/i', $agent)){
            $browser = 'Opera';
        }
        else{
            $browser = "others";
        }

        return $browser;
    }

    /**
     * @access public 获取当前端口
     * @return int 返回当前使用的端口
     * */
    public function getHostPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * @access public 获取服务器域名
     * @return string 返回服务器的域名
     * */
    public function getDomain()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * @access public 获取php安装路径
     * @return string 返回php安装路径
     * */
    public function getPhpInstallDir()
    {
       return DEFAULT_INCLUDE_PATH;
    }

    /**
     * @access public 获取客户端IP
     * @return string ip地址
     * */
    public function getClientIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @access public 判断扩展是否存在
     * @param string $name 扩展名称
     * @return bool 存在true 不存在 false
     * */
    public function isExtension($name)
    {
        if(extension_loaded($name)){
            return true;
        }
        return false;
    }

    /**
     * @access public 判断是否是ajax
     * @return bool Ajax返回true 不是返回false
     * */
    public function isAjax()
    {
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            return true;
        }
        return false;
    }

}