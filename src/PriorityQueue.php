<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class PriorityQueue
 * @package Limen\Fastrq
 */
class PriorityQueue extends Base
{
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function push($values)
    {
        $script = $this->loadScript('priority_queue_push');
        return $this->callEval($this->composeEvalParams($script, $values));
    }

    public function pop($count = 1)
    {
        $script = $this->loadScript('priority_queue_pop');
        $pop = $this->callEval($this->composeEvalParams($script, $count));
        if ($count === 1) {
            return $pop ? [$pop[0], $pop[1]] : null;
        }
        return array_chunk($pop, 2);
    }

    public function range($start = '-inf', $end = '+inf')
    {
        return $this->connect()->zrange(
            $this->id,
            $start,
            $end,
            ['withscores' => true]
        );
    }

    public function length()
    {
        return $this->connect()->zcard($this->id);
    }

    protected function composeEvalParams($script, $values)
    {
        $evalParams = [$script, 1, $this->id];
        if (is_array($values)) {
            foreach ($values as $member => $score) {
                $evalParams[] = $score;
                $evalParams[] = $member;
            }
        } else {
            $evalParams[] = $values;
        }

        return $evalParams;
    }
}