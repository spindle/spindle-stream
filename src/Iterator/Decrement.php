<?php
namespace Spindle\Stream\Iterator;

final class Decrement implements
    \SeekableIterator,
    \Spindle\Stream\InfiniteInterface
{
    private $i;
    private $init;

    function __construct($init=\PHP_INT_MAX)
    {
        $this->i = $this->init = $init;
    }

    function seek($position)
    {
        $this->i = $position;
    }

    function current()
    {
        return $this->i;
    }

    function key()
    {
        return $this->i;
    }

    function next()
    {
        --$this->i;
    }

    function rewind()
    {
        $this->i = $this->init;
    }

    function valid()
    {
        return true;
    }
}
