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

    /**
     * Push member(s)
     *
     * @param array $members key(s) as member(s) and value(s) as score(s)
     *
     * @return int The queue's length
     */
    public function push($members)
    {
        $script = $this->loadScript('priority_queue_push');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Pop member(s)
     * Returns
     * 1-dimension array if count == 1 and the queue is not empty
     * null if count == 1 and the queue is empty
     * else 2-dimension array
     *
     * @param int $count
     *
     * @return array|null
     */
    public function pop($count = 1)
    {
        $script = $this->loadScript('priority_queue_pop');
        $pop = $this->runScript($script, $this->composePopArgs($count));
        if ($count === 1) {
            return $pop ? [$pop[0], $pop[1]] : null;
        }
        return array_chunk($pop, 2);
    }

    /**
     * Push only if the member not already inside the queue
     * Returns
     * The queue's length and a success flag
     *
     * @param mixed $member
     * @param mixed $score
     *
     * @return array [int, bool]
     */
    public function pushNI($member, $score)
    {
        $script = $this->loadScript('priority_queue_push_not_in');
        $raw = $this->runScript($script, $this->composePushArgs([$member => $score]));
        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push only if the queue not already exist
     *
     * Returns
     * false if the queue already exists
     * else the queue's length after push
     *
     * @param array $members
     *
     * @return int|bool
     */
    public function pushNE($members)
    {
        $script = $this->loadScript('priority_queue_push_ne');
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
     * @param array $members
     *
     * @return int|bool
     */
    public function pushAE($members)
    {
        $script = $this->loadScript('priority_queue_push_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return $raw;
    }

    public function range($start, $end)
    {
        $raw = $this->connect()->zrange(
            $this->id,
            $start,
            $end,
            ['withscores' => true]
        );
        $list = [];
        foreach ($raw as $member => $score) {
            $list[] = [$member, $score];
        }

        return $list;
    }

    public function length()
    {
        return $this->connect()->zcard($this->id);
    }

    protected function composePushArgs($values)
    {
        $args = [1, $this->id];
        foreach ($values as $member => $score) {
            $args[] = $score;
            $args[] = $member;
        }

        return $args;
    }
}