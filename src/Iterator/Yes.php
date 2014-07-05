<?php
namespace Spindle\Stream\Iterator;

final class Yes implements
    \SeekableIterator,
    \Spindle\Stream\Infinite
{
    private $yes, $i=0;

    function __construct($yes = 'yes')
    {
        $this->yes = $yes;
    }

    function seek($position)
    {
        $this->i = $position;
    }

    function current()
    {
        return $this->yes;
    }

    function key()
    {
        return $this->i;
    }

    function next()
    {
        ++$this->i;
    }

    function rewind()
    {
        $this->i = 0;
    }

    function valid()
    {
        return true;
    }
}
