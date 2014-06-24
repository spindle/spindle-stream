<?php
namespace Spindle\Stream\Iterator;

class Map extends \IteratorIterator implements
    \Spindle\Stream\InfiniteInterface
{
    private $func;

    function __construct(\Iterator $it, $func)
    {
        $this->func = $func;
        parent::__construct($it);
    }

    function current()
    {
        $func = $this->func;
        return $func(parent::current());
    }
}
