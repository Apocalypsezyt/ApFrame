<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/14
 * Time: 8:34
 */

namespace lib\ext;


class UrlParmEncode
{
    protected $key_url_md_5 = 'Acp_oa yq_baz*x'; //可以更换为其它的加密标记，可以自由发挥

    protected function keyED($txt,$encrypt_key){
        $encrypt_key = md5($encrypt_key);
        $ctr=0;
        $tmp = "";
        for($i=0;$i<strlen($txt);$i++)
        {
            if ($ctr==strlen($encrypt_key))
                $ctr=0;
            $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
            $ctr++;
        }
        return $tmp;
    }
    protected function encrypt($txt,$key)   {
        $encrypt_key = md5(mt_rand(0,100));
        $ctr=0;
        $tmp = "";
        for ($i=0;$i<strlen($txt);$i++)
        {
            if ($ctr==strlen($encrypt_key))
                $ctr=0;
            $tmp.=substr($encrypt_key,$ctr,1) . (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
            $ctr++;
        }
        return $this->keyED($tmp,$key);
    }

    protected function decrypt($txt,$key){
        $txt = $this->keyED($txt,$key);
        $tmp = "";
        for($i=0;$i<strlen($txt);$i++)
        {
            $md5 = substr($txt,$i,1);
            $i++;
            $tmp.= (substr($txt,$i,1) ^ $md5);
        }
        return $tmp;
    }
    public function encrypt_url($url,$key){
        return rawurlencode(base64_encode($this->encrypt($url,$key)));
    }
    protected function decrypt_url($url,$key){
        return $this->decrypt(base64_decode(rawurldecode($url)),$key);
    }

    public function geturl($str,$key){
        $str = $this->decrypt_url($str,$key);
        $url_array = explode('&',$str);
        if (is_array($url_array))
        {
            foreach ($url_array as $var)
            {
                $var_array = explode("=",$var);
                $vars[$var_array[0]]=$var_array[1];
            }
        }
        return $vars;
    }

    public function encodeUrl($url)
    {
        return rawurlencode(base64_encode($this->encrypt($url,$this->key_url_md_5)));
    }

    public function decodeUrl($str){
        $str = $this->decrypt_url($str,$this->key_url_md_5);
        $url_array = explode('&',$str);
        $vars = array();
        if (is_array($url_array))
        {
            foreach ($url_array as $var)
            {
                $var_array = explode("=",$var);
                $vars[$var_array[0]]=$var_array[1];
            }
        }
        return $vars;
    }


}