<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/24
 * Time: 9:06
 */

namespace apphp\Core;


class Group implements \Iterator
{

    protected $info = array();
    protected $index= 0;

    function __construct()
    {
        $this->index = 0;
    }

    public function __set($name, $value)
    {
        $this->info[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->info[$name]))
        {
            return $this->info[$name];
        }

        return "暂未定义";
    }

    /**
     * @method public 验证该数组是否为空
     * @return bool 空返回true  否则返回null
     * */
    public function isEmpty()
    {
        if(count($this->info)==0||empty($this->info))
        {
            return true;
        }

        return false;
    }

    /**
     * @method public 返回数据的数量
     * @return int 返回数据总数量
     * */
    public function count(): int
    {
        return count($this->info);
    }

    /**
     * @method public 返回所有数据
     * @return array
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * @method public 返回当前位置的元素
     * */
    public function current()
    {
        return $this->info[$this->index];
    }

    /**
     * @method public 返回当前元素的键
     * @return int 当前的元素键
     * */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @method public 向前移动到下一个元素
     * */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * @method public 重置到初始位置
     * */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * @method public 验证当前索引存不存在
     * @return bool 返回是否设置
     * */
    public function valid()
    {
        return isset($this->info[$this->index]);
    }

}