<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class OfCappedQueue
 * @package Limen\Fastrq
 */
class OfCappedQueue extends CappedQueue
{
    public function push($values)
    {
        $script = $this->loadScript('of_capped_queue_push');
        $values = (array)$values;
        array_unshift($values, $this->cap);
        return $this->callEval($this->composeEvalParams($script, $values));
    }
}