<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class OfCappedPriorityQueue
 * @package Limen\Fastrq
 */
class OfCappedPriorityQueue extends CappedPriorityQueue
{
    public function push($values)
    {
        $script = $this->loadScript('of_capped_priority_queue_push');
        list($len, $out) = $this->callEval($this->composePushEvalParams($script, $values));
        return [$len, array_chunk($out, 2)];
    }
}