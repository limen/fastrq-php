<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class OfCappedDeque
 * @package Limen\Fastrq
 */
class OfCappedDeque extends CappedDeque
{
    public function push_front($values)
    {
        $script = $this->loadScript('of_capped_deque_push_front');
        $values = (array)$values;
        array_unshift($values, $this->cap);
        return $this->callEval($this->composeEvalParams($script, $values));
    }

    public function push_back($values)
    {
        $script = $this->loadScript('of_capped_deque_push_back');
        $values = (array)$values;
        array_unshift($values, $this->cap);
        return $this->callEval($this->composeEvalParams($script, $values));
    }
}