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
        array_unshift($args, null);
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

    function toArray(&$result = null)
    {
        return $result = $this->arr;
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

    function filter($func)
    {
        $func = Lambda::create('$_', $func, true);
        $filtered = array_filter($this->arr, $func);
        return new self($filtered);
    }

    function reduce($func, $initial=null)
    {
        $func = Lambda::create('$a,$b', $func, true);
        return array_reduce($this->arr, $func, $initial);
    }

    function walk($func)
    {
        $func = Lambda::create('$_', $func, false);
        array_walk($this->arr, $func);
    }

    function sort($collator = \SORT_REGULAR)
    {
        if (is_int($collator)) {
            sort($this->arr, $collator);
        } else {
            $collator = Lambda::create('$a,$b', $collator, true);
            usort($this->arr, $collator);
        }

        return $this;
    }

    function rsort($collator = \SORT_REGULAR)
    {
        $this->sort($collator);
        $this->arr = array_reverse($this->arr);
        return $this;
    }

    function sortBy($collector, $flags = \SORT_REGULAR, $order = \SORT_ASC)
    {
        if (!is_callable($collator)) throw new \InvalidArgumentException;
        $collector = Lambda::create('$_', $collector, true);
        $mapped = array_map($collector, $this->arr);
        array_multisort($mapped, $flags, $order, $this->arr);

        return $this;
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
        return $result = $this;
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

        foreach ($args as $i => $a) {
            if ($a instanceof self) {
                $args[$i] = $a->arr;
            }
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
