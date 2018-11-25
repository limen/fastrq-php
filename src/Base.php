<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

use Predis\Client;

/**
 * Class Base
 * @package Limen\Fastrq
 */
class Base
{
    protected $redis;

    protected $id;

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function connect()
    {
        if (is_null($this->redis)) {
            $this->redis = new Client();
        }

        return $this->redis;
    }

    public function loadScript($command)
    {
        return Loader::load($command);
    }

    public function expire($ttl)
    {

    }

    public function expireAt($timestamp)
    {

    }

    public function ttl()
    {

    }

    public function pttl()
    {

    }

    public function range($start, $end)
    {

    }

    public function length()
    {

    }

    public function destruct()
    {
        $this->connect()->del($this->id);
    }

    protected function callEval($params)
    {
        return call_user_func_array([$this->connect(), 'eval'], $params);
    }

    protected function composeEvalParams($script, $values)
    {
        $evalParams = [$script, 1, $this->id];
        if (is_array($values)) {
            $evalParams = array_merge($evalParams, $values);
        } else {
            $evalParams[] = $values;
        }

        return $evalParams;
    }

    protected function removeNilPop($pop)
    {
        if (!$pop) {
            return $pop;
        }
        $length = count($pop);
        foreach ($pop as $k => $v) {
            if (is_null($v)) {
                $length = $k;
                break;
            }
        }
        return array_slice($pop, 0, $length);
    }
}