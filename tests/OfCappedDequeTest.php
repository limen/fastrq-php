<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class OfCappedDequeTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\OfCappedDeque
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\OfCappedDeque('fastrq_of_capped_deque', 3);
        $this->queue->destruct();
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->queue->pushBack([1,2]);
        $this->assertEquals($this->queue->pushFront([3,4]), [3, ['2']]);
        $this->assertEquals($this->queue->pushFront([5,6]), [3, ['1', '3']]);
        $this->assertEquals($this->queue->range(0,-1), [ '6', '5', '4']);
        $this->assertEquals($this->queue->popBack(), '4');
        $this->assertEquals($this->queue->popFront(), '6');
    }

}