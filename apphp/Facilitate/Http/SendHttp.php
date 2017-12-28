<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/25
 * Time: 9:58
 */

namespace apphp\Facilitate\http;


class SendHttp
{

    protected $url;
    protected $get_info;
    protected $post_info;

    function __construct($url)
    {
        $this->url = $url;
    }

    /*
     *
     *   $http = new \lib\ext\SendHttp('http://127.0.0.1/backstage/module/handle.php');
     *   $data = array(
     *       'big'=>'user',
     *       'small'=>'getall',
     *   );
     *   echo $http->sendGet($data); // 支持返回Json
     *
     *   @data 数据以数组形式
    */
    public function sendGet(array $data)
    {
        $str = '?';
        foreach($data as $key=>$value)
        {
            $str.=$key.'='.$value.'&';
        }

        $html = file_get_contents($this->url.$str);

        return $html;
    }

    public function sendPost($data)
    {
//        初始化curl
        $curl = curl_init();
//        指定URL
        curl_setopt($curl,CURLOPT_URL,$this->url);
//        设置头文件信息作为数据流输出
        curl_setopt($curl,CURLOPT_HEADER,1);
//        设置获取信息以文件流的形式返回，而不是输出
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
//        设置POST请求提交
        curl_setopt($curl,CURLOPT_PORT,1);
//        设置POST的数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
//        执行CURL命令
        $data = curl_exec($curl);
//        关闭URL请求
        curl_close($curl);

        return $data;
    }

    public function sentPut($data)
    {
//        初始化curl
        $curl = curl_init();
        $header[] = "Content-type:image/jpeg";  //定义header，可以加多个
//        指定URL
        curl_setopt($curl,CURLOPT_URL,$this->url);
//        改成put请求
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'put');
//        是否显示状态头
        curl_setopt($curl,CURLOPT_HEADER,0);
//        定义header
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
//        定义提交的数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
//        执行CURL命令
        $data = curl_exec($curl);
//        关闭URL请求
        curl_close($curl);

        return $data;
    }
}