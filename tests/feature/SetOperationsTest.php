<?php
namespace Spindle\Stream\Tests;

use Spindle\Stream;

class SetOperationsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function createOddNumbersStream()
    {
        $natural0 = Stream::increment(0);
        $natural1 = Stream::increment(1);

        Stream::zip($natural0, $natural1)
            ->map('$_[0] + $_[1]', '$_[0] + $_[1]')
            ->export($oddStream)
            ->limit(0, 5)
            ->toArray($odd);
        self::assertEquals(array(1,3,5,7,9), array_values($odd));

        $oddStream->map('$_ - 1')
            ->limit(0, 5)
            ->toArray($even);
        self::assertEquals(array(0,2,4,6,8), array_values($even));
    }
}
