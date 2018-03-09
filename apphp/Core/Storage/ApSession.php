<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 3/8/2018
 * Time: 21:00
 */

namespace apphp\Core\Storage;


class ApSession
{

    /**
     * @access protected $driver 驱动
     * */
    protected $driver;
    /**
     * @access protected static $info 用户数据
     * */
    protected static $info;

    function __construct()
    {
        $driver = strtolower(SESSION_DRIVER);
        $this->driver = $driver;
        if($this->generated()){
            $this->init();
        }
    }

    /**
     * @access public 开启Session
     * */
    public static function start()
    {
        $instance = new static();
        $instance->generated();
    }

    /**
     * @access protected cookie生成session,若存在则延长他的时间
     * @return bool
     * */
    protected function generated()
    {
        if(!isset($_COOKIE['apframe_session'])){
            $session_id = md5(uniqid(microtime(true), true));
            setcookie('apframe_session', $session_id, time() + 7200);
            switch ($this->driver){
                case 'file':
                    file_put_contents(ROOT_PATH . 'runtime/session/' . $session_id, '');
                    break;
                case 'redis':
                    $redis = new \Redis();
                    $redis->set($session_id, '');
                    $redis->close();
                    break;
                case 'mysql':
                    $mysql = new \mysqli(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE,MYSQL_PORT);
                    $sql = "INSERT INTO `session`(`key`,`value`) VALUES('${session_id}', '') ";
                    $mysql->query($sql);
                    $mysql->close();
                    break;
            }
            return false;
        }
        else{
            setcookie('apframe_session', $_COOKIE['apframe_session'],  time() + 7200);
            return true;
        }
    }

    /**
     * @access protected 将数据读入静态变量
     * */
    protected function init()
    {
        switch ($this->driver){
            case 'file':
                        $file_name = ROOT_PATH . '/runtime/session/' . $_COOKIE['apframe_session'];
                        $data = file_get_contents($file_name);
                        self::$info = unserialize($data);
                        break;
            case 'redis':
                        $redis = new \Redis();
                        $redis->connect(REDIS_HOST, REDIS_PORT);
                        $info = $redis->get($_COOKIE['apframe_session']);
                        self::$info = unserialize($info);
                        $redis->close();
                        break;
            case 'mysql':
                        $mysql = new \mysqli(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE,MYSQL_PORT);
                        $sql = "SELECT * FROM `session` WHERE `key`='${_COOKIE['apframe_session']}'";
                        $query = $mysql->query($sql);
                        $info = mysqli_fetch_assoc($query)['value'];
                        self::$info = unserialize($info);
                        $mysql->close();
                        break;
        }
    }

    /**
     * @access public 设置session
     * @param $key string 键名
     * @param $data mixed 数据
     * */
    public function set($key , $data)
    {
        // 万一是对象
        if(is_object($data)){
            $data = serialize($data);
        }
        self::$info[$key] = $data;
        switch ($this->driver){
            case 'file':
                        $serialize_data = serialize(self::$info);
                        file_put_contents(ROOT_PATH . '/runtime/session/' . $_COOKIE['apframe_session'], $serialize_data);
                        break;
            case 'redis':
                        $serialize_data = serialize(self::$info);
                        $redis = new \Redis();
                        $redis->connect(REDIS_HOST, REDIS_PORT);
                        $redis->set($_COOKIE['apframe_session'], $serialize_data);
                        $redis->close();
                        break;
            case 'mysql':
                        $serialize_data = serialize(self::$info);
                        $mysql = new \mysqli(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE,MYSQL_PORT);
                        $sql = "INSERT INTO `session`(`key`,`value`) VALUES('${_COOKIE['apframe_session']}', '${serialize_data}') ";
                        $mysql->query($sql);
                        $mysql->close();
                        break;

        }
    }

    /**
     * @access public 获取session
     * @param string $key 键值
     * @return mixed 返回数据
     * */
    public function get($key)
    {
        $info = self::$info[$key];

        // 万一是对象
        if(preg_match('/O:\d+:"\S+":\d+:\{/', $info)){
            $info = unserialize($info);
        }

        return $info;
    }
}