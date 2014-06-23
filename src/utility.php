<?php
/**
 * spindle/bobine
 * @license CC0-1.0 (Public Domain) https://creativecommons.org/publicdomain/zero/1.0/
 */
namespace Spindle;

class Stream
{
    static function _($init)
    {
        if (is_array($init)) {
            return new Stream\ListObject($init);
        }
        if (!is_object($init)) {
            throw new \InvalidArgumentException(gettype($init));
        }
        if ($init instanceof \IteratorAggregate) {
            return new Stream\Finite($init->getIterator());
        }
        if ($init instanceof \Iterator) {
            return new Stream\Finite($init);
        }
        if ($init instanceof \Traversable) {
            $init = new \IteratorIterator($init);
        } else {
            $init = new \ArrayIterator((array)$init);
        }
        return new Stream\Finite($init);
    }

    /**
     * @param int $initial
     * @return Stream\Iterator\Sequence
     */
    static function sequence($initial = 0)
    {
        return new Stream\Infinite(new Stream\Iterator\Sequence($initial));
    }

    static function range($offset, $count)
    {
        return self::sequence($offset)->limit(0, $count);
    }

    /**
     * @param mixed $yes
     * @return Bobine\Infinite
     */
    static function yes($yes = 'yes')
    {
        return new Stream\Infinite(new Stream\Iterator\Yes($yes));
    }

    static function recur($init, $func)
    {
        $func = Stream\Lambda::create('$_', $func, true);
        return new Stream\Infinite(
            new Stream\Iterator\RecurrenceRelation($init, $func)
        );
    }

    static function recur2($init1, $init2, $func)
    {
        $func = Stream\Lambda::create('$a,$b', $func, true);
        return new Stream\Infinite(
            new Stream\Iterator\RecurrenceRelation2($init1, $init2, $func)
        );
    }

    static function generate($func)
    {
        return new Stream\Infinite(new Invoke($func));
    }

    static function zip()
    {
        $mit = new \MultipleIterator;
        $finite = false;
        foreach (func_get_args() as $it) {
            if ($it instanceof Stream\FiniteInterface) {
                $finite = true;
            }
            if ($it instanceof \IteratorAggregate) {
                $it = $it->getIterator();
            }
            $mit->attachIterator($it);
        }
        return $finite ? new Stream\Finite($mit) : new Stream\Infinite($mit);
    }

    static function glob($glob)
    {
        return new Stream\Finite(new \GlobIterator($glob));
    }

    static function byte($string)
    {
        return new Stream\Finite(new Stream\Iterator\Byte($string));
    }

    static function mbstring($string, $encoding = 'UTF-8')
    {
    }

    static function streamline($filename)
    {
    }

    static function streambyte($filename)
    {
    }

    static function secondRange($since, $until=null)
    {
    }

    static function minuteRange($since, $until=null)
    {
    }

    static function hourRange($since, $until=null)
    {
    }

    static function dateRange($since, $until=null)
    {
    }

    static function monthRange($since, $until=null)
    {
    }

    static function yearRange($since, $until=null)
    {
    }
}
