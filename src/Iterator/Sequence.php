<?php
namespace Spindle\Stream\Iterator;

final class Sequence implements \SeekableIterator
{
    private $i;
    private $offset;

    function __construct($offset=0)
    {
        $this->i = $this->offset = $offset;
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
        ++$this->i;
    }

    function rewind()
    {
        $this->i = $this->offset;
    }

    function valid()
    {
        return true;
    }
}
