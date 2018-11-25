<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\Stack
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\Stack('fastrq_stack');
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(1), 1);
        $this->assertEquals($this->queue->push([2,3]), 3);
        $this->assertEquals($this->queue->pop(), '3');
        $this->assertEquals($this->queue->pop(2), ['2','1']);
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
    }
}