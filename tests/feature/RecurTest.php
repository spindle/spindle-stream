<?php
namespace Spindle\Stream\Tests;

use Spindle\Stream;

class RecurTest extends \PHPUnit_Framework_TestCase
{
    function testRecur()
    {
        Stream::iterate(0, '$_ + 1')
            ->limit(0, 5)
            ->export($stream);

        foreach ($stream as $i => $v) {
            self::assertSame($i, $v);
        }
    }

    function testRecur2()
    {
        Stream::iterate2(0, 1, '$a + $b')
            ->limit(0,7)
            ->toArray($fib);

        self::assertSame(array(0,1,1,2,3,5,8), $fib);
    }
}
