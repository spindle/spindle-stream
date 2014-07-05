<?php
namespace Spindle\Stream\Iterator;

class Map extends \IteratorIterator implements
    \Spindle\Stream\InfiniteInterface
{
    private $func, $kfunc;

    function __construct(\Iterator $it, $func, $kfunc=null)
    {
        $this->func = $func;
        $this->kfunc = $kfunc;
        parent::__construct($it);
    }

    function current()
    {
        $func = $this->func;
        return $func(parent::current());
    }

    function key()
    {
        $kfunc = $this->kfunc;
        if ($kfunc) {
            return $kfunc(parent::key());
        } else {
            return parent::key();
        }
    }
}
