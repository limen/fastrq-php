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

    /**
     * Push member(s)
     *
     * @param mixed $members
     *
     * @return int The queue's length
     */
    public function push($members)
    {
        $script = $this->loadScript('stack_push');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Pop member(s)
     * Returns
     * string if count == 1 and the queue is not empty
     * null if count == 1 and the queue is empty
     * else array
     *
     * @param int $count
     *
     * @return array|string|null
     */
    public function pop($count = 1)
    {
        $script = $this->loadScript('stack_pop');
        $pop = $this->runScript($script, $this->composePopArgs($count));
        if ($count === 1) {
            return $pop ? $pop[0] : null;
        }

        return $pop;
    }

    /**
     * Push only if the member not already inside the queue
     * Returns
     * The queue's length and a success flag
     *
     * @param mixed $member
     *
     * @return array [int, bool]
     */
    public function pushNI($member)
    {
        $script = $this->loadScript('stack_push_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));
        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push only if the queue not already exist
     *
     * Returns
     * false if the queue already exists
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return int|bool
     */
    public function pushNE($members)
    {
        $script = $this->loadScript('stack_push_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }
        return $raw;
    }

    /**
     * Push only if the queue already exists
     *
     * Returns
     * false if the queue not already exist
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return int|bool
     */
    public function pushAE($members)
    {
        $script = $this->loadScript('stack_push_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return $raw;
    }

    /**
     * Get the first index of a member
     *
     * @param $member
     *
     * @return int|null
     */
    public function indexOfOne($member)
    {
        $script = $this->loadScript('stack_indexof');
        $raw = $this->runScript($script, $this->composePushArgs($member));

        return $raw[0] >= 0 ? $raw[0] : null;
    }

    /**
     * Get indexes of member(s)
     *
     * @param array $members
     *
     * @return array key(s) as member(s), value(s) as index(es)
     */
    public function indexOfMany($members)
    {
        $script = $this->loadScript('stack_indexof');
        $raw = $this->runScript($script, $this->composePushArgs($members));

        $indexes = [];
        foreach ($members as $i => $member) {
            $indexes[$member] = $raw[$i + 1] >= 0 ? $raw[$i + 1] : null;
        }

        return $indexes;
    }

    public function length()
    {
        return $this->id ? $this->connect()->llen($this->id) : 0;
    }

    public function range($start, $end)
    {
        return $this->id ? $this->connect()->lrange($this->id, $start, $end) : [];
    }
}