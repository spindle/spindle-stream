<?php
namespace Spindle\Stream\Tests;

use Spindle\Stream;

class LambdaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function createFunction()
    {
        $f = Stream\Lambda::create('$a,$b', '$a + $b');
        self::assertSame(3, $f(1, 2));

        //2nd call
        $f2 = Stream\Lambda::create('$a,$b', '$a + $b');
        self::assertSame($f, $f2);

        $f = Stream\Lambda::create('$a,$b', function($a,$b){ return $a + $b; });
        self::assertSame(3, $f(1,2));

        $f = Stream\Lambda::create('$_', 'strtoupper');
        self::assertSame('ABC', $f('abc'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    function invalidCreateFunction()
    {
        $f = Stream\Lambda::create('$_', 1);
    }
}
