<?php
namespace Spindle\Stream\Iterator;

class Reject extends \FilterIterator
{
    private $callback;

    function __construct(\Iterator $it, $callback)
    {
        $this->callback = $callback;
        parent::__construct($it);
    }

    function accept()
    {
        $f = $this->callback;
        return !$f($this->current(), $this->key(), $this->getInnerIterator());
    }
}
