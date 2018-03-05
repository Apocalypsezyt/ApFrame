<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 3/5/2018
 * Time: 20:44
 */

namespace apphp\Core\Facilitate;

/**
 * 图片压缩类
 * 类操作例子：
 * $img_compress =  new ImgCompress('图片的路径');
 * $img_compress->compressImage('压缩后图片的名称');
 * */
class ImgCompress
{

    // 图片资源
    protected $image;
    protected $image_info;
    protected $image_src;

    function __construct($src)
    {
        $this->image_src = $src;
    }

    function __destruct()
    {
        if($this->image)
            imagedestroy($this->image);
    }

    /**
     * @access protected 打开图片
     * */
    protected function openImage()
    {
        // 获取图片信息
        $info = getimagesize($this->image_src);
        if(!empty($info)){
            $width = $info[0];
            $height = $info[1];
            $type = substr($info['mime'], strripos($info['mime'],'/')+1);
        }
        $this->image_info = array(
            'width' => $width,
            'height' => $height,
            'type' => $type,
            'attr' => $info['bits']
        );
        // 生成图片类型的方法
        $func = "imagecreatefrom" . $type;
        $this->image = $func($this->image_src);
    }

    /**
     * @access protected 操作图片
     * */
    protected function thumpImage()
    {
        $width = $this->image_info['width'];
        $height = $this->image_info['height'];
        $image_thump = imagecreatetruecolor($width, $height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $this->image, 0, 0, 0, 0, $width, $height, $width, $height);
        imagedestroy($this->image);
        $this->image = $image_thump;
    }

    /**
     * @access public 压缩图片
     * @param string $name 图片名称
     * */
    public function compressImage($name)
    {
        $this->openImage();
        $this->saveImage($name);
    }

    /**
     * @access protected 保存图片到硬盘
     * @param string $name 图片的新名字
     * */
    protected function saveImage($name)
    {
        $allow_extension = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp','.gif'];
        //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名
        $ext =  strrchr($name ,".");
        $source_ext = strrchr($this->image_src ,".");
        if(!empty($ext)){
            $ext = strtolower($ext);
        }
        if(!empty($source_ext)){
            $source_ext = strtolower($source_ext);
        }
        //有指定目标名扩展名
        if(!empty($ext) && in_array($ext, $allow_extension)){
            $image_save_name = $name;
        }
        elseif(!empty($source_ext) && in_array($source_ext, $allow_extension)){
            $image_save_name = $name.$source_ext;
        }
        else{
            $image_save_name = $name.$this->image_info['type'];
        }
        $funcs = "image".$this->image_info['type'];
        $funcs($this->image,$image_save_name);
    }
}