<?php
namespace Spindle\Stream\Iterator;

class RecurrenceRelation implements \Iterator
{
    private
          $init
        , $func
        , $i = 0
        , $prev = null
        , $currentCache = null
        ;

    function __construct($init, $func)
    {
        $this->init = $init;
        $this->func = $func;
    }

    function key()
    {
        return $this->i;
    }

    function current()
    {
        if ($this->i) {
            $func = $this->func;
            return $this->currentCache = $func($this->prev);
        } else {
            return $this->currentCache = $this->init;
        }
    }

    function next()
    {
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
    }
}
