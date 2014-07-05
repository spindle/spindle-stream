<?php
namespace Spindle\Stream;

final class Finite extends Base implements FiniteInterface
{
    function append()
    {
        $ait = new \AppendIterator;
        $ait->append($this->it);
        $infinite = false;
        foreach (func_get_args() as $it) {
            if ($it instanceof InfiniteInterface || $it instanceof \InfiniteIterator) {
                $infinite = true;
            }
            if ($it instanceof \IteratorAggregate) {
                $it = $it->getIterator();
            }
            $ait->append($it);
        }

        return $infinite ? new Infinite($ait) : new Finite($ait);
    }

    function cycle()
    {
        return $this->infinite();
    }

    function inifinite()
    {
        $it = new \InfiniteIterator($this->it);
        return new Infinite($it);
    }

    function sum(&$result = null)
    {
        $arr = $this->toList();
        return $result = $arr->sum();
    }

    function average(&$result = null)
    {
        $arr = $this->toList();
        return $result = $arr->average();
    }

    function product(&$result = null)
    {
        $arr = $this->toList();
        return $result = $this->product();;
    }

    function sort($comparator = \SORT_REGULAR)
    {
        $arr = $this->toList();
        $arr->sort($comparator);
        return $arr;
    }


    function count()
    {
        return iterator_count($this->it);
    }

    function countTo(&$result = null)
    {
        return $result = $this->count();
    }

    function max(&$result = null)
    {
        return $result = max(iterator_to_array($this->it));
    }

    function min(&$result = null)
    {
        return $result = min(iterator_to_array($this->it));
    }

    function walk($func)
    {
        $func = Lambda::create('$_,$i', $func, false);
        $rfunc = new \ReflectionFunction($func);
        $argc = $rfunc->getNumberOfParameters();

        if ($argc === 1) {
            foreach ($this->it as $key => $val) {
                $func($val);
            }
        } else {
            foreach ($this->it as $key => $val) {
                $func($val, $key);
            }
        }
    }

    function reduce($fn, $init=null, &$result = null)
    {
        $arr = new ListObject(iterator_to_array($this->it));
        $result = $arr->reduce($fn, $init);
        return $result;
    }

    function toArray(&$result = null)
    {
        return $result = iterator_to_array($this->it);
    }

    function toList(&$result = null)
    {
        return $result = new ListObject(iterator_to_array($this->it));
    }

    function __call($method, $args)
    {
        $list = $this->toList();
        $func = array($list, $method);
        return call_user_func_array($func, $args);
    }
}
