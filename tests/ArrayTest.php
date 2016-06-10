<?php

namespace Elixir\Test\STDLib;

use PHPUnit_Framework_TestCase;
use function Elixir\STDLib\array_get;
use function Elixir\STDLib\array_has;
use function Elixir\STDLib\array_set;

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
        $this->assertEquals(true, array_has('test1', $data));
        $this->assertFalse(array_has('test4', $data));
        $this->assertFalse(array_has(0, $data));
        
        // get test
        $this->assertEquals(1, array_get('test1', $data));
        $this->assertEquals(1, array_get('test5', $data)); // type problem
        $this->assertEquals(null, array_get('test4', $data));
        $this->assertEquals('default', array_get('test4', $data, 'default'));
        $this->assertContains(2, array_get('test2', $data));
        
        // set test
        array_set('test6', false, $data);
        $this->assertCount(6, $data);
        
        return $data;
    }
}
