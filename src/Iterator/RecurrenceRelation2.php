<?php
namespace Spindle\Stream\Iterator;

class RecurrenceRelation2 implements \Iterator
{
    private
          $init
        , $init2
        , $func
        , $i = 0
        , $prev = null
        , $prevprev = null
        , $currentCache = null
        ;

    function __construct($init, $init2, $func)
    {
        $this->init = $init;
        $this->init2 = $init2;
        $this->func = $func;
    }

    function key()
    {
        return $this->i;
    }

    function current()
    {
        if ($this->currentCache) return $this->currentCache;
        if ($this->i === 0) return $this->currentCache = $this->init;
        if ($this->i === 1) return $this->currentCache = $this->init2;

        $func = $this->func;
        return $this->currentCache = $func($this->prevprev, $this->prev);
    }

    function next()
    {
        $this->prevprev = $this->prev;
        $this->prev = isset($this->currentCache)
                    ? $this->currentCache
                    : $this->current();
        $this->currentCache = null;
        ++$this->i;
    }

    function valid()
    {
        return true;
    }

    function rewind()
    {
        $this->i = 0;
        $this->prev = null;
        $this->prevprev = null;
    }
}
