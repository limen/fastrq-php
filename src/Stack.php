<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class Stack
 * @package Limen\Fastrq
 */
class Stack extends Base
{
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function push($values)
    {
        $script = $this->loadScript('stack_push');
        return $this->callEval($this->composeEvalParams($script, $values));
    }

    public function pop($count = 1)
    {
        $script = $this->loadScript('stack_pop');
        $pop = $this->callEval($this->composeEvalParams($script, $count));
        if ($count === 1) {
            return $pop ? $pop[0] : null;
        }
        return $this->removeNilPop($pop);
    }
}