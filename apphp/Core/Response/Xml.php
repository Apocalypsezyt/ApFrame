<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 3/5/2018
 * Time: 18:29
 */

namespace apphp\Core\Response;


trait Xml
{

    protected $version = '1.0';
    protected $encoding = 'UTF-8';
    protected $root = 'root';
    protected $xmlWrite;

    function __construct()
    {
        $this->xmlWrite = new \XMLWriter();
    }

    /**
     * @access public 将数组变成xml格式数据
     * @param array $info 以数组形式存在的数据
     * @param bool $eIsArray element是不是数组
     * @return string xml
     * */
    public function toXml($info, $eIsArray = FALSE)
    {
        if(!$eIsArray){
            $this->xmlWrite->openMemory();
            $this->xmlWrite->startDocument($this->version, $this->encoding);
            $this->xmlWrite->startElement($this->root);
        }

        foreach ($info as $key=>$value){
            if(is_array($value)){
                $this->xmlWrite->startElement($key);
                $this->toXml($value, true);
                $this->xmlWrite->endElement();
                continue;
            }
            $this->xmlWrite->writeElement($key, $value);
        }

        if(!$eIsArray){
            $this->xmlWrite->endElement();
            return $this->xmlWrite->outputMemory(true);
        }
    }
}