<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

use PHPUnit\Framework\TestCase;

class CappedStackTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\CappedStack
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedStack('fastrq_capped_stack', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(1), 1);
        $this->assertEquals($this->queue->pop(), '1');
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
        $this->assertEquals($this->queue->push(['apple', 'banana', 'pear', 'grape']), 'err_qof');
        $this->assertEquals($this->queue->push(['apple', 'banana', 'pear']), 3);
        $this->assertEquals($this->queue->push(['apple', 'banana', 'pear']), 'err_qf');
        $this->assertEquals($this->queue->pop(2), ['pear', 'banana']);
        $this->assertEquals($this->queue->pop(), 'apple');
        $this->assertEquals($this->queue->pop(2), []);

        $this->queue->push(['apple', 'banana']);
        $this->assertEquals($this->queue->pushNI('apple'), [2, false]);
        $this->assertEquals($this->queue->pushNI('strawberry'), [3, true]);

        $this->assertFalse($this->queue->pushNE('strawberry'));
        $this->queue->pop();
        $this->assertEquals($this->queue->pushAE('kiwi'), 3);
    }

    public function testDestruct()
    {
        $this->queue->push(1);
        $this->assertTrue($this->queue->destruct());
        $this->assertFalse($this->queue->destruct());
    }

    public function testHelpers()
    {
        $this->assertEquals($this->queue->pushNE(['hello', 'world']), 2);
        $this->assertEquals($this->queue->length(), 2);
        $this->assertEquals($this->queue->range(0, -1), ['world', 'hello']);
        $this->assertEquals($this->queue->range(0, 1), ['world', 'hello']);
        $this->assertEquals($this->queue->range(0, 0), ['world']);
        $this->assertEquals($this->queue->range(1, 1), ['hello']);
        $this->assertEquals($this->queue->range(1, 2), ['hello']);
    }

    public function testCommandCached()
    {
        $this->assertTrue($this->queue->isCommandCached('capped_stack_push'));
        $this->assertTrue($this->queue->isCommandCached('capped_stack_pop'));
        $this->assertTrue($this->queue->isCommandCached('capped_stack_push_not_in'));
        $this->assertTrue($this->queue->isCommandCached('capped_stack_push_ne'));
        $this->assertTrue($this->queue->isCommandCached('capped_stack_push_ae'));
    }
}