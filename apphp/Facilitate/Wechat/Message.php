<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/27
 * Time: 13:04
 */

namespace apphp\Facilitate\Wechat;


class Message
{
    protected $_token;
    protected $from_user_name;  // 来自哪个用户
    protected $to_user_name;    // 发送给哪个用户
    protected $keyword;    // 用户关键字
    protected $info_array;  // 数据组
    protected $func_array;  // 方法组

    /*
     *
     *  初始化一个token
     *
     * */
    function __construct()
    {
        $this->_token = WECHAT_TOKEN;
    }

    /*
     *
     * 验证TOKEN或者响应用户请求
     *
     * */
    public function verifyOrRespond()
    {
        if(isset($_GET['echostr']))
        {
            $this->verify();
        }
        else
        {
            $this->responseMsg();
        }
    }

    /*
     *
     *  验证通过判断
     *  此段用于验证微信 Token 是否通过
     * 与checkHashToken()耦合
     *
     * */
    public function verify()
    {
        $verify_str = $_GET['echostr']; //获取这次验证字符串
        if($this->checkHashToken())
        {
            echo $verify_str;
            exit();
        }
    }

    /*
     *
     *  验证服务端的TOKEN是否正确
     *
     * */
    public function checkHashToken()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $tmpArr = array($this->_token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     *
     *  通过用户的关键字返回对应的信息
     *
     * */
    public function responseMsg()
    {
//      获取来自用户的相关信息
        $user_info = $this->responseUserInfo();

        if($user_info)
        {
            if(isset($this->func_array[$this->keyword]))
            {
                $this->func_array[$this->keyword]($this->keyword);
            }
            else
            {
                $this->responseText("暂无此项，请联系公众号管理人员");
            }
        }

    }

    /*
    *
    *  获取用户发出的信息（来自哪个用户，关键词）
    *
    * */
    public function responseUserInfo()
    {
//        是否接收到数据
        $post_str = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!empty($post_str))
        {
            $post_obj = simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);

            $this->from_user_name = $post_obj->FromUserName;
            $this->to_user_name = $post_obj->ToUserName;

            $this->keyword = trim($post_obj->Content);

            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     *
     *  注册一条回复信息
     *
     * */
    public function register($key,$info)
    {
        $this->info_array[$key] = $info;
        switch($info['type'])
        {
            case 'text':
                $this->func_array[$key] = function($keyword){
                    $this->responseText($this->info_array[$keyword]['content']);
                };
                break;
            case 'image':
                $this->func_array[$key] = function($keyword){
                    $this->responseImage($this->info_array[$keyword]['url']);
                };
                break;
            default:   return false;
        }

        return true;
    }

    /*
     *
     *  对用户返回文本
     *
     * */
    protected function responseText($content_str)
    {
        $template = $this->useTemplate('text');
        $resultStr = sprintf($template, $this->from_user_name, $this->to_user_name, time(), 'text', $content_str);
        echo $resultStr;
    }

    // DEBUG!
    /*
     *
     *  对用户返回图片
     *
     * */
    protected function responseImage($url)
    {
        $template = $this->useTemplate('image');
        $resultStr = sprintf($template, $this->from_user_name, $this->to_user_name, time(), 'image', $url);
        echo $resultStr;
    }

    /*
     *  需要使用的模板
     *  返回模板文件
     * */
    public function useTemplate($type)
    {
        $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName> 
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                      </xml>";
        switch($type)
        {
            case 'text':
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName> 
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
                              </xml>";
                break;
            case 'image':
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%]]></MsgType>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <MediaId>125</MediaId>
                                <MsgId>1234567890123456</MsgId>
                            </xml>";
                break;
        }

        return $template;
    }
}