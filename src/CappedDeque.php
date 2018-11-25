<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class CappedDeque
 * @package Limen\Fastrq
 */
class CappedDeque extends Deque
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

    public function push_front($values)
    {
        $script = $this->loadScript('capped_deque_push_front');
        $values = (array)$values;
        array_unshift($values, $this->cap);
        return $this->callEval($this->composeEvalParams($script, $values));
    }

    public function push_back($values)
    {
        $script = $this->loadScript('capped_deque_push_back');
        $values = (array)$values;
        array_unshift($values, $this->cap);
        return $this->callEval($this->composeEvalParams($script, $values));
    }
}