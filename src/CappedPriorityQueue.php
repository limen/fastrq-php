<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class CappedPriorityQueue
 * @package Limen\Fastrq
 */
class CappedPriorityQueue extends PriorityQueue
{
    protected $cap;

    public function __construct($id = null, $cap = null)
    {
        parent::__construct($id);
        $this->cap = $cap;
    }

    public function setCap($cap)
    {
        $this->cap = $cap;
        return $this;
    }

    public function getCap()
    {
        return $this->cap;
    }

    public function push($values)
    {
        $script = $this->loadScript('capped_priority_queue_push');
        return $this->callEval($this->composePushEvalParams($script, $values));
    }

    protected function composePushEvalParams($script, $values)
    {
        $evalParams = [$script, 1, $this->id, $this->cap];
        foreach ($values as $member => $score) {
            $evalParams[] = $score;
            $evalParams[] = $member;
        }

        return $evalParams;
    }
}