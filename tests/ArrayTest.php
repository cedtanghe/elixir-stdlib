<?php

namespace Elixir\Test\STDLib;

use Elixir\STDLib\ArrayUtils;
use PHPUnit_Framework_TestCase;

/**
 * @author Nicola Pertosa <nicola.pertosa@gmail.com>
 */
class ArrayTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $data = ['test0' => '0', 
                 'test1' => 1,
                 'test2' => [2],
                 'test3' => true,
                 'test5' => '1'];
        
        // has test
        $this->assertEquals(true, ArrayUtils::has('test1', $data));
        $this->assertFalse(ArrayUtils::has('test4', $data));
        $this->assertFalse(ArrayUtils::has(0, $data));
        
        // get test
        $this->assertEquals(1, ArrayUtils::get('test1', $data));
        $this->assertEquals(1, ArrayUtils::get('test5', $data)); // type problem
        $this->assertEquals(null, ArrayUtils::get('test4', $data));
        $this->assertEquals('default', ArrayUtils::get('test4', $data, 'default'));
        $this->assertContains(2, ArrayUtils::get('test2', $data));
        
        // set test
        ArrayUtils::set('test6', false, $data);
        $this->assertCount(6, $data);
        
        return $data;
    }
}
