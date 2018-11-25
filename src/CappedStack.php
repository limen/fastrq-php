<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class CappedStack
 * @package Limen\Fastrq
 */
class CappedStack extends Stack
{
    protected $cap;

    public function __construct($id = null, $cap = null)
    {
        parent::__construct($id);
        $this->cap = $cap;
    }

    public function push($values)
    {
        $script = $this->loadScript('capped_stack_push');
        $values = (array)$values;
        array_unshift($values, $this->cap);
        return $this->callEval($this->composeEvalParams($script, $values));
    }
}