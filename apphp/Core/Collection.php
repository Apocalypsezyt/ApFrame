<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/24
 * Time: 9:06
 */

namespace apphp\Core;

// 数组式访问接口，php预定义接口 http://php.net/manual/zh/class.arrayaccess.php
use ArrayAccess;
// 数组迭代器 http://php.net/manual/zh/class.arrayiterator.php
use ArrayIterator;
// 统计元素对象个数接口 http://php.net/manual/zh/class.countable.php
use Countable;
// 聚合式迭代器接口，php预定义接口  http://php.net/manual/zh/class.iteratoraggregate.php
use IteratorAggregate;
// JSON序列话接口 http://php.net/manual/zh/class.jsonserializable.php
use JsonSerializable;
// 类 Collection 实现了ArrayAccess, Countable, IteratorAggregate, JsonSerializable接口

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    // 数据存储变量
    protected $items = [];
    // 构造函数
    public function __construct($items = [])
    {
        // 转化成数组，并赋值给变量items
        $this->items = $this->convertToArray($items);
    }
    // 装载/生成/制作等等，怎么叫法都可以，就是生成一个新结果集合
    public static function make($items = [])
    {
        // new static即是获取最终调用时的类[后续有介绍]
        return new static($items);
    }

    /**
     * 是否为空
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }
    // 返回数组
    public function toArray()
    {
        // array_map ：数组里的每个元素都经过这个回调函数处理
        return array_map(
        // 这是一个匿名回调函数
            function ($value) {
                return (
                    /*// instanceof 检测变量$value是否属于Model类的实例
                    $value instanceof Model ||*/
                    // instanceof 检测变量$value是否属于当前 Collection类的实例
                    $value instanceof self) ?
                    // 返回数组
                    $value->toArray() : $value;
            }, $this->items);
    }
    // 返回变量 items 所有数据集
    public function all()
    {
        return $this->items;
    }

    /**
     * 合并数组
     *
     * @param  mixed $items
     * @return static
     */
    public function merge($items)
    {
        // new static即是获取最终调用时的类[后续有介绍]
        return new static(
        // array_merge 合并数组
            array_merge($this->items,
                // 转换成数组
                $this->convertToArray($items)));
    }

    /**
     * 比较数组，返回差集
     *
     * @param  mixed $items
     * @return static
     */
    public function diff($items)
    {
        return new static(
        // 数组之间的差集
            array_diff($this->items, $this->convertToArray($items)));
    }

    /**
     * 交换数组中的键和值
     *
     * @return static
     */
    public function flip()
    {
        return new static(
        //  array_flip 交换数组中的键和值
            array_flip($this->items));
    }

    /**
     * 比较数组，返回交集
     *
     * @param  mixed $items
     * @return static
     */
    public function intersect($items)
    {
        return new static(
        // array_intersect 比较数组，返回交集
            array_intersect($this->items, $this->convertToArray($items)));
    }

    /**
     * 返回数组中所有的键名
     *
     * @return static
     */
    public function keys()
    {
        return new static(
        // array_keys 返回数组中所有的键名
            array_keys($this->items));
    }

    /**
     * 删除数组的最后一个元素（出栈）
     *
     * @return mixed
     */
    public function pop()
    {
        // 删除数组的最后一个元素（出栈）
        return array_pop($this->items);
    }

    /**
     * 通过使用用户自定义函数，以字符串返回数组
     *
     * @param  callable $callback
     * @param  mixed    $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        // array_reduce 用回调函数迭代地将数组简化为单一的值
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * 以相反的顺序返回数组。
     *
     * @return static
     */
    public function reverse()
    {
        return new static(
        // array_reverse 以相反的顺序返回数组
            array_reverse($this->items));
    }

    /**
     * 删除数组中首个元素，并返回被删除元素的值
     *
     * @return mixed
     */
    public function shift()
    {
        // array_shift 删除数组中首个元素，并返回被删除元素的值
        return array_shift($this->items);
    }

    /**
     * 把一个数组分割为新的数组块.
     *
     * @param  int  $size
     * @param  bool $preserveKeys
     * @return static
     */
    public function chunk($size, $preserveKeys = false)
    {
        $chunks = [];
        //array_chunk 把一个数组分割为新的数组块.
        foreach (array_chunk($this->items, $size, $preserveKeys) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
     * 在数组开头插入一个元素
     * @param mixed $value
     * @param null  $key
     * @return int
     */
    public function unshift($value, $key = null)
    {
        // 检测 $key 是否为null
        if (is_null($key)) {
            // array_unshift 在数组开头插入一个元素
            array_unshift($this->items, $value);
        } else {
            //两个数组组合成为新的数组，并赋值给items
            $this->items = [$key => $value] + $this->items;
        }
    }

    /**
     * 给每个元素执行个回调
     *
     * @param  callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        //数组循环
        foreach ($this->items as $key => $item) {
            // 数组回调，如果不明白的，看PHP基础吧
            if ($callback($item, $key) === false) {
                break;
            }
        }
        //返回 当前对象
        return $this;
    }

    /**
     * 用回调函数过滤数组中的元素
     * @param callable|null $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(
            // array_filter用回调函数过滤数组中的元素
                array_filter($this->items, $callback));
        }

        return new static(
        // array_filter用回调函数过滤数组中的元素
            array_filter($this->items));
    }

    /**
     * 返回数组中指定的一列
     * @param      $column_key
     * @param null $index_key
     * @return array
     */
    public function column($column_key, $index_key = null)
    {
        // 检测函数是否存在,如果存在，则使用PHP内置函数处理，不存在则使用我们自己实现的方法
        if (function_exists('array_column')) {
            // array_column 返回数组中指定的列
            return array_column($this->items, $column_key, $index_key);
        }

        $result = [];
        foreach ($this->items as $row) {
            $key    = $value = null;
            $keySet = $valueSet = false;
            // array_key_exists 检查给定的键名或索引$index_key是否存在于数组$row中
            if (null !== $index_key && array_key_exists($index_key, $row)) {
                //如果存在，设置变量$keySet值为真
                $keySet = true;
                //
                $key    = (string)$row[$index_key];
            }
            if (null === $column_key) {
                $valueSet = true;
                $value    = $row;
            } elseif (is_array($row) && array_key_exists($column_key, $row)) {
                $valueSet = true;
                $value    = $row[$column_key];
            }
            if ($valueSet) {
                if ($keySet) {
                    $result[$key] = $value;
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * 对数组排序
     *
     * @param  callable|null $callback
     * @return static
     */
    public function sort(callable $callback = null)
    {
        $items = $this->items;

        $callback ?
            // uasort 使用用户自定义的$callback比较函数对$items数组中的值进行排序并保持索引关联
            uasort($items, $callback) : uasort($items,
            // 这是个匿名函数
            function ($a, $b) {
                // 检测两个变量的值是否相等，相等返回0
                if ($a == $b) {
                    return 0;
                }
                // 如果变量$a小于$b时，返回-1，否则都返回1
                return ($a < $b) ? -1 : 1;
            });

        return new static($items);
    }

    /**
     * 将数组打乱
     *
     * @return static
     */
    public function shuffle()
    {
        $items = $this->items;
        // 将数组打乱
        shuffle($items);

        return new static($items);
    }

    /**
     * 截取数组
     *
     * @param  int  $offset
     * @param  int  $length
     * @param  bool $preserveKeys
     * @return static
     */
    public function slice($offset, $length = null, $preserveKeys = false)
    {
        return new static(
        // array_slice 截取  $this->items 数组中 $offset 和 $length 之间的数组
        // $preserveKeys 默认会重新排序并重置数组的数字索引
            array_slice($this->items, $offset, $length, $preserveKeys));
    }

    // ArrayAccess
    public function offsetExists($offset)
    {
        // 检查给定的$offset键名或索引是否存在于$this->items数组中
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        //返回 $this->items数组中指定 $offset键名或索引 的数据或者对象
        return $this->items[$offset];
    }
    // 数组 中增加一条新数据或者对象
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }
    // 删除指定 $offset键名或索引 的数据或者对象
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    //Countable
    public function count()
    {
        // 返回 计算数组中的单元数目或对象中的属性个数
        return count($this->items);
    }

    //IteratorAggregate
    public function getIterator()
    {
        // 返回 创建外部迭代器
        return new ArrayIterator($this->items);
    }

    //JsonSerializable
    public function jsonSerialize()
    {
        // 返回序列化数组
        return $this->toArray();
    }

    /**
     * 转换当前数据集为JSON字符串
     * @access public
     * @param integer $options json参数
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        // 返回JSON 格式数据
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        // 返回JSON 格式数据
        return $this->toJson();
    }

    /**
     * 转换成数组
     *
     * @param  mixed $items
     * @return array
     */
    protected function convertToArray($items)
    {
        // instanceof 检测变量$items是否属于当前类Collection的实例
        if ($items instanceof self) {
            // 执行all方法，返回所有
            return $items->all();
        }
        // 强制转换为数组
        return (array)$items;
    }
}