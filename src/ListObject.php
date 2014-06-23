<?php
namespace Spindle\Stream;

final class ListObject implements
    FiniteInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    private $arr;

    static function zip()
    {
        $args = func_get_args();
        array_unshift(null, $args);
        return new self(call_user_func_array('array_map', $args));
    }

    function __construct(array $arr)
    {
        $this->arr = $arr;
    }

    function getIterator()
    {
        return new \ArrayIterator($this->arr);
    }

    function fromArray(array $arr)
    {
        $this->arr = $arr;
    }

    function toArray()
    {
        return $this->arr;
    }

    function push(/* arg1, arg2, arg3, ... */)
    {
        foreach (func_get_args() as $v) {
            $this->arr[] = $v;
        }
        return $this;
    }

    function pop()
    {
        return array_pop($this->arr);
    }

    function unshift(/* arg1, arg2, arg3, ... */)
    {
        $args = array_merge(array(&$this->arr), func_get_args());
        call_user_func_array('array_unshift', $args);
        return $this;
    }

    function shift()
    {
        return array_shift($this->arr);
    }

    function map($func)
    {
        $func = Lambda::create('$_', $func, true);
        $mapped = array_map($func, $this->arr);
        return new self($mapped);
    }

    function walk($func)
    {
        $func = Lambda::create('$_', $func, true);
        array_walk($this->arr, $func);
    }

    function count()
    {
        return count($this->arr);
    }

    function offsetExists($offset)
    {
        return array_key_exists($this->arr, $offset);
    }

    function offsetGet($offset)
    {
        return $this->arr[$offset];
    }

    function offsetSet($offset, $value)
    {
        $this->arr[$offset] = $value;
    }

    function offsetUnset($offset)
    {
        unset($this->arr[$offset]);
    }

    function export(&$result)
    {
        $result = $this;
        return $this;
    }

    function __call($method, $args)
    {
        $method = preg_replace('/[A-Z]/', '_\0', $method);
        $lastPos = strlen($method) - 1;
        if ($method[$lastPos] === '_') {
            $method = substr($method, 0, -1);
            $chain = true;
        } else {
            $chain = false;
        }

        $func = 'array_' . $method;
        if (!function_exists($func)) {
            throw new \BadMethodCallException("$func is not exists.");
        }

        $args = array_merge(array(&$this->arr), $args);
        $res = call_user_func_array($func, $args);

        if (is_array($res)) {
            return new self($res);
        } elseif ($chain) {
            return $this;
        } else {
            return $res;
        }
    }
}
