<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */
use PHPUnit\Framework\TestCase;

class CappedDequeTest extends TestCase
{
    /**
     * @var \Limen\Fastrq\CappedDeque
     */
    private $queue;

    protected function setUp()
    {
        $this->queue = new \Limen\Fastrq\CappedDeque('fastrq_capped_deque', 9);
    }

    protected function tearDown()
    {
        $this->queue->destruct();
    }

    public function testPushPop()
    {
        $this->assertEquals($this->queue->pushBack('hello'), 1);
        $this->assertEquals($this->queue->pushFront('world'), 2);
        $this->assertEquals($this->queue->pushFront(['!', '!!']), 4);
        $this->assertEquals($this->queue->popFront(), '!!');
        $this->assertEquals($this->queue->popBack(), 'hello');
        $this->assertEquals($this->queue->popFront(2), ['!', 'world']);
        $this->assertEquals($this->queue->pushBack(['apple', 'banana', 'pear', 'grape']), 4);
        $this->assertEquals($this->queue->popFront(2), ['apple', 'banana']);
        $this->assertEquals($this->queue->popFront(), 'pear');
        $this->assertEquals($this->queue->popFront(2), ['grape']);

        $this->queue->pushBack(['apple', 'banana', 'pear', 'grape']);
        $this->assertEquals($this->queue->pushBackNI('apple'), [4, false]);
        $this->assertEquals($this->queue->pushBackNI('strawberry'), [5, true]);
        $this->assertEquals($this->queue->pushFrontNI('peach'), [6, true]);
        $this->assertEquals($this->queue->pushFrontNI('peach'), [6, false]);

        $this->assertFalse($this->queue->pushFrontNE('strawberry'));
        $this->assertFalse($this->queue->pushBackNE('strawberry'));
        $this->assertEquals($this->queue->pushFrontAE('kiwi'), 7);
        $this->assertEquals($this->queue->pushBackAE('kiwi'), 8);

        $this->assertEquals($this->queue->pushFront([1,2]), 'err_qof');
        $this->assertEquals($this->queue->pushBack([1,2]), 'err_qof');
        $this->assertEquals($this->queue->pushFrontAE([1,2]), 'err_qof');
        $this->queue->pushBack(1);
        $this->assertEquals($this->queue->pushFront([1,2]), 'err_qf');
        $this->assertEquals($this->queue->pushBack([1,2]), 'err_qf');
        $this->assertEquals($this->queue->pushFrontNI(1), 'err_qf');
        $this->assertEquals($this->queue->pushBackNI(1), 'err_qf');
        $this->assertEquals($this->queue->pushFrontAE([1,2]), 'err_qf');
    }

    public function testDestruct()
    {
        $this->queue->pushFront(1);
        $this->assertTrue($this->queue->destruct());
        $this->assertFalse($this->queue->destruct());
    }

    public function testHelpers()
    {
        $this->assertEquals($this->queue->pushBackNE(['hello', 'world']), 2);
        $this->assertEquals($this->queue->length(), 2);
        $this->assertEquals($this->queue->range(0, -1), ['hello', 'world']);
        $this->assertEquals($this->queue->range(0, 1), ['hello', 'world']);
        $this->assertEquals($this->queue->range(0, 0), ['hello']);
        $this->assertEquals($this->queue->range(1, 1), ['world']);
        $this->assertEquals($this->queue->range(1, 2), ['world']);
        $this->assertEquals($this->queue->pushFront(['!', '!!']), 4);
        $this->assertEquals($this->queue->range(0, -1), ['!!', '!', 'hello', 'world']);
        $this->assertEquals($this->queue->range(0, 1), ['!!', '!']);
        $this->assertEquals($this->queue->range(0, 0), ['!!']);
        $this->assertEquals($this->queue->range(1, 1), ['!']);
        $this->assertEquals($this->queue->range(1, 2), ['!', 'hello']);
    }

    public function testCommandCached()
    {
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_front'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_front_not_in'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_front_ne'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_front_ae'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_back'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_back_not_in'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_back_ne'));
        $this->assertTrue($this->queue->isCommandCached('capped_deque_push_back_ae'));
    }
}