<?php

namespace app\test\Controller;

use apphp\Core\Controller;

class resource extends Controller
{
    /**
      *  @access public 显示该模块中的所有数据 GET方法
      */
    public function index()
    {
        
    }
    
    /**
      * @access public 显示对应id的数据 GET方法
      * @param int | string $id 
      */
    public function show($id)
    {
        echo $id;
    }
    
    /**
      * @access public 显示创建的表单 GET方法
      */
    public function create()
    {
    
    }
    
    /**
      * @access public 显示对应id的修改表单 GET方法
      * @param int | string $id 
      */
    public function edit($id)
    {
    
    }
    
    /**
      * @access public 创建数据
      */
    public function store()
    {
    
    }
    
    /**
      * @access public 修改对应ID的数据
      * @param int | string $id 
      */
    public function update($id)
    {
    
    }
    
    /**
      * @access public 删除对应ID的数据
      * @param int | string $id 
      */
    public function delete($id)
    {
    
    }
}