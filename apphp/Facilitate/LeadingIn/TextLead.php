<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 0:35
 */

namespace apphp\Facilitate\LeadingIn;


Class TextLead
{

    private $fp;

    /*
     *  构造函数：打开文件流
     *
     *  $file 文件目录
     * */
    function __construct($file)
    {
        $this->fp = fopen($file,'r');
    }

    /*
     *  将文件里的内容按照自己的格式来分割
     *
     *  field 字段 传入时array(field1,field2)
     * */
    public function leadInfo(array $field)
    {
        $content = '';
        $array = array();
        $code_start = 'sscanf($content,"';
        $code_1 = '';
        $code_2 = '';
        $code_end = ');';
        foreach($field as $value)
        {
            $code_1 .= '%s ';
            $code_2 .= ',$array[$i]["'.$value.'"]';
        }
        $code = $code_start.$code_1.'"'.$code_2.$code_end;
        $i = 0;
        while (!feof($this->fp))
        {
            $content = fgets($this->fp);
            eval($code);
            $i++;
        }

        return $array;
    }

}

