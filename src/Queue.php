<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/17
 */
namespace Limen\Fastrq;

/**
 * Queue
 *
 * Class Queue
 * @package Limen\Rediq
 */
class Queue extends Base
{
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function push($values)
    {
        $script = $this->loadScript('queue_push');
        return $this->callEval(
            $this->composeEvalParams($script, $values)
        );
    }

    public function pop($count = 1)
    {
        $script = $this->loadScript('queue_pop');
        $pop = $this->callEval($this->composeEvalParams($script, $count));
        if ($count === 1) {
            return $pop ? $pop[0] : null;
        }
        return $this->removeNilPop($pop);
    }

    public function range($start, $end)
    {
        return $this->id ? $this->connect()->lrange($this->id, $start, $end) : null;
    }

    public function length()
    {
        return $this->id ? $this->connect()->llen($this->id) : null;
    }
}