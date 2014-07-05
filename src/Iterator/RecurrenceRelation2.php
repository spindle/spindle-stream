<?php
namespace Spindle\Stream\Iterator;

class RecurrenceRelation2 implements \Iterator
{
    private
          $init
        , $init2
        , $func
        , $i = 0
        , $prev = array(null, null)
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
        if ($this->i < 2) {
            return $this->currentCache = $this->i === 0 ? $this->init : $this->init2;
        }

        $func = $this->func;
        return $this->currentCache = $func($this->prev[$this->i & 1], $this->prev[~$this->i & 1]);
    }

    function next()
    {
        $this->prev[$this->i & 1] = isset($this->currentCache)
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
        $this->prev = array(null, null);
    }
}
