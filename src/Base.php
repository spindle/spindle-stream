<?php
namespace Spindle\Stream;

abstract class Base implements \IteratorAggregate
{
    /** @type Iterator $it */
    protected $it;

    function __construct(\Iterator $it)
    {
        $this->it = $it;
    }

    function getIterator()
    {
        return $this->it;
    }

    function map($func, $kfunc=null)
    {
        $func = Lambda::create('$_', $func, true);
        if ($kfunc) {
            $kfunc = Lambda::create('$_', $kfunc, true);
        }
        return new static(new Iterator\Map($this->it, $func, $kfunc));
    }

    function filter($func)
    {
        $func = Lambda::create('$_,$i,$it', $func, true);
        return new static(new Iterator\Filter($this->it, $func));
    }

    function reject($func)
    {
        $func = Lambda::create('$_,$i,$it', $func, true);
        return new static(new Iterator\Reject($this->it, $func));
    }

    function cache($flags = \CachingIterator::FULL_CACHE)
    {
        return new static(new \CachingIterator($this->it, $flags));
    }

    function limit($a, $b=null)
    {
        if (isset($b)) {
            $it = new \LimitIterator($this->it, $a, $b);
            return new Finite($it);
        } else {
            $it = new \LimitIterator($this->it, $a);
            if ($this instanceof Infinite) {
                return new Infinite($it);
            } else {
                return new Finite($it);
            }
        }
    }

    function export(&$return)
    {
        return $return = $this;
    }
}
