<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class OfCappedQueueTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\OfCappedQueue
     */
    protected $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\OfCappedQueue('fastrq_of_capped_queue', 3);
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->push(1), [1, []]);
        $this->assertEquals($this->queue->push([2,3,4]), [3, ['1']]);
        $this->assertEquals($this->queue->pushNI(2), [3, [], false]);
        $this->assertEquals($this->queue->pop(), 2);
        $this->assertEquals($this->queue->pop(2), ['3','4']);
        $this->assertEquals($this->queue->pop(), null);
        $this->assertEquals($this->queue->pop(2), []);

        $this->assertEquals($this->queue->pushNE(['hello', 'world']), [2, []]);
        $this->assertFalse($this->queue->pushNE(['hello', 'world']));
        $this->assertEquals($this->queue->pushAE('!'), [3, []]);
        $this->assertEquals($this->queue->pushAE('!'), [3, ['hello']]);
    }

    public function testDestruct()
    {
        $this->queue->push(1);
        $this->assertTrue($this->queue->destruct());
        $this->assertFalse($this->queue->destruct());
    }

    public function testHelpers()
    {
        $this->assertEquals($this->queue->pushNE(['hello', 'world']), [2, []]);
        $this->assertEquals($this->queue->length(), 2);
        $this->assertEquals($this->queue->range(0, -1), ['hello', 'world']);
        $this->assertEquals($this->queue->range(0, 1), ['hello', 'world']);
        $this->assertEquals($this->queue->range(0, 0), ['hello']);
        $this->assertEquals($this->queue->range(1, 1), ['world']);
        $this->assertEquals($this->queue->range(1, 2), ['world']);
    }

    public function testCommandCached()
    {
        $this->assertTrue($this->queue->isCommandCached('of_capped_queue_push'));
        $this->assertTrue($this->queue->isCommandCached('of_capped_queue_pop'));
        $this->assertTrue($this->queue->isCommandCached('of_capped_queue_push_not_in'));
        $this->assertTrue($this->queue->isCommandCached('of_capped_queue_push_ne'));
        $this->assertTrue($this->queue->isCommandCached('of_capped_queue_push_ae'));
    }
}