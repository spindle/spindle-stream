<?php
namespace Spindle\Stream\Tests;

use Spindle\Stream;

class ListObjectTest extends \PHPUnit_Framework_TestCase
{
    function testListBehaveLikeArray()
    {
        $arr = Stream::_(array(1,2,3));
        self::assertSame(1, $arr[0]);
        self::assertSame(2, $arr[1]);
        self::assertSame(3, $arr[2]);

        self::assertArrayHasKey(2, $arr->toArray());

        $arr[0] = 'a';
        self::assertSame('a', $arr[0]);

        unset($arr[2]);
        self::assertCount(2, $arr);
    }

    function testListIsCountable()
    {
        $arr = Stream::_(array(1,2,3));
        self::assertSame(3, count($arr));
    }

    function testListIsSortable()
    {
        $arr = Stream::_(array(3,2,1));
        $arr->sort();
        self::assertSame(array(1,2,3), $arr->toArray());
        $arr->rsort();
        self::assertSame(array(3,2,1), $arr->toArray());

        $arr = Stream::_(array(1,2,10));
        $arr->sort(\SORT_STRING);
        self::assertSame(array(1,10,2), $arr->toArray());
        $arr->rsort(\SORT_STRING);
        self::assertSame(array(2,10,1), $arr->toArray());

        $arr = Stream::_(array('1b','2c','3a'));
        $arr->sort('ord($a[1]) - ord($b[1])');
        self::assertSame(array('3a','1b','2c'), $arr->toArray());
    }

    function testListArray()
    {
        $arr = Stream::_(array(1,2,3));
        self::assertSame(array(1,2,3), $arr->toArray());
    }

    function testListCanPushPullShiftUnshift()
    {
        $arr = Stream::_(array(1,2,3));
        $arr->push(4,5);
        self::assertSame(4, $arr[3]);
        self::assertSame(5, $arr[4]);

        self::assertSame(5, $arr->pop());
        self::assertCount(4, $arr);

        $arr = Stream::_(array(1,2,3));
        $arr->unshift(4,5);
        self::assertSame(4, $arr[0]);
        self::assertSame(5, $arr[1]);

        self::assertSame(4, $arr->shift());
        self::assertCount(4, $arr);
    }

    function testSetOperations()
    {
        $arr1 = Stream::_(array(2,4,6));
        $arr2 = Stream::_(array(1,2,3));

        self::assertSame(array(2), $arr1->intersect($arr2)->toArray());
        self::assertSame(array(4,6), $arr1->diff($arr2)->values()->toArray());
        self::assertSame(array(2,4,6,1,2,3), $arr1->merge($arr2)->toArray());
        self::assertSame(array(2,4,6,1,3), $arr1->merge($arr2)->unique()->values()->toArray());

        $arr3 = Stream::_(array(0,1))->directProduct(array(0,1));
        self::assertSame(array(array(0,0),array(0,1),array(1,0),array(1,1)), $arr3->toArray());
    }
}
