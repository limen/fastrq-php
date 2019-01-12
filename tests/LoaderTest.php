<?php

use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    public function testLoad()
    {
        $script = \Limen\Fastrq\Loader::load('capped_deque_push_front_ne');
        $this->assertTrue((bool)$script);
        try {
            \Limen\Fastrq\Loader::load('abc');
        } catch (\Exception $e) {
            $this->assertTrue(strpos($e->getMessage(), 'not found') !== false);
        }
    }
}