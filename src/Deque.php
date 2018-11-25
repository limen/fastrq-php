<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class Deque
 * @package Limen\Fastrq
 */
class Deque extends Base
{
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function push_front($values)
    {
        $script = $this->loadScript('deque_push_front');
        return $this->callEval($this->composeEvalParams($script, $values));
    }

    public function push_back($values)
    {
        $script = $this->loadScript('deque_push_back');
        return $this->callEval($this->composeEvalParams($script, $values));
    }

    public function pop_front($count = 1)
    {
        $script = $this->loadScript('deque_pop_front');
        $pop = $this->callEval($this->composeEvalParams($script, $count));
        if ($count === 1) {
            return $pop ? $pop[0] : null;
        }
        return $this->removeNilPop($pop);
    }

    public function pop_back($count = 1)
    {
        $script = $this->loadScript('deque_pop_back');
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