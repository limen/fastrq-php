<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class CappedPriorityQueueTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\CappedPriorityQueue
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedPriorityQueue('fastrq_capped_priority_queue', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(['google' => 1]), 1);
        $this->assertEquals($this->queue->pop(), ['google', '1']);
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);
        $this->assertEquals($this->queue->push(['apple' => 1, 'banana' => 2, 'pear' => 3, 'grape' => 4]), 'err_qof');
        $this->assertEquals($this->queue->push(['apple' => 1, 'banana' => 2, 'pear' => 3]), 3);
        $this->assertEquals($this->queue->push(['google' => 1]), 'err_qf');
        $this->assertEquals($this->queue->pop(2), [['apple', '1'], ['banana', '2']]);
        $this->assertEquals($this->queue->pop(2), [['pear', '3']]);

        $this->assertEquals($this->queue->push(['apple' => 1, 'banana' => 2]), 2);
        $this->assertEquals($this->queue->pushNI('apple', 1), [2, false]);
        $this->assertEquals($this->queue->pushNI('strawberry', 2), [3, true]);
        $this->queue->pop();

        $this->assertFalse($this->queue->pushNE(['strawberry' => 4]));
        $this->assertEquals($this->queue->pushAE(['kiwi' => 6.6]), 3);
    }

    public function testDestruct()
    {
        $this->queue->push(['kiwi' => 1]);
        $this->assertTrue($this->queue->destruct());
        $this->assertFalse($this->queue->destruct());
    }

    public function testHelpers()
    {
        $this->assertEquals($this->queue->pushNE(['hello' => 1, 'world' => 2]), 2);
        $this->assertEquals($this->queue->length(), 2);
        $this->assertEquals($this->queue->range(0, -1), [['hello', '1'], ['world', '2']]);
        $this->assertEquals($this->queue->range(0, 1), [['hello', '1'], ['world', '2']]);
        $this->assertEquals($this->queue->range(0, 0), [['hello', '1']]);
        $this->assertEquals($this->queue->range(1, 1), [['world', '2']]);
        $this->assertEquals($this->queue->range(1, 2), [['world', '2']]);
    }

    public function testCommandCached()
    {
        $this->assertTrue($this->queue->isCommandCached('capped_priority_queue_push'));
        $this->assertTrue($this->queue->isCommandCached('capped_priority_queue_pop'));
        $this->assertTrue($this->queue->isCommandCached('capped_priority_queue_push_not_in'));
        $this->assertTrue($this->queue->isCommandCached('capped_priority_queue_push_ne'));
        $this->assertTrue($this->queue->isCommandCached('capped_priority_queue_push_ae'));
    }
}