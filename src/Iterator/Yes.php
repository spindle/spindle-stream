<?php
namespace Spindle\Stream\Iterator;

final class Yes implements
    \SeekableIterator,
    \Spindle\Stream\Infinite
{
    private $yes;

    function __construct($yes = 'yes')
    {
        $this->yes = $yes;
    }

    function seek($position)
    {
    }

    function current()
    {
        return $this->yes;
    }

    function key()
    {
        return 1;
    }

    function next()
    {
    }

    function rewind()
    {
    }

    function valid()
    {
        return true;
    }
}
