<?php

namespace AvalonComposerUtil;

/**
 * 自定义数组，实现es6形式的lamda方法
 *
 * Class MyArray
 * @package App\Util
 */
class MyArray
{

    public $arr = [];
    public $length = 0;

    function __construct(array $arr)
    {
        $this->arr = $arr;
        $this->length = sizeof($arr);
    }

    public function foreach(callable $callback)
    {
        $i = 0;
        foreach ($this->arr as $d) {
            $callback($d, $i);
            $i++;
        }
    }

    public function map(callable $callback)
    {
        $arr = array_map($callback, $this->arr);
        return new MyArray($arr);
    }

    public function filter(callable $callback)
    {
        $new_arr = new MyArray([]);
        $i = 0;
        foreach ($this->arr as $d) {
            if ($callback($d, $i, $this->arr) === true) {
                $new_arr->push($d);
            }
            $i++;
        }
        return new MyArray($new_arr->arr);
    }

    public function concat(MyArray ...$valueN)
    {
        $arr = $this->arr;
        foreach ($valueN as $value) {
            $arr = array_merge($arr, $value->arr);
        }
        return new MyArray($arr);
    }

    public function find(callable $callback)
    {
        foreach ($this->arr as $d) {
            if ($callback($d) === true) {
                return $d;
                break;
            }
        }
    }

    public function some(callable $callback)
    {
        foreach ($this->arr as $d) {
            if ($callback($d) === true) {
                return true;
                break;
            }
        }
        return false;
    }

    public function join(string $separator)
    {
        return implode($separator, $this->arr);
    }

    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->arr, $callback, $initial);
    }

    public function push($value)
    {
        array_push($this->arr, $value);
        $this->length = sizeof($this->arr);
    }

    public function includes($value)
    {
        $notExist = array_search($value, $this->arr);
        return $notExist !== false;
    }

    public function sort(callable $callback)
    {
        usort($this->arr, $callback);
    }

    public function slice($index, $length)
    {
        $newArr = array_slice($this->arr, $index, $length);
        return new MyArray($newArr);
    }
}