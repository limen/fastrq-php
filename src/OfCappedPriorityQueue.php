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
    /**
     * Push member(s)
     * Returns
     * The queue's length after push and the member(s) been forced out
     *
     * @param array $members
     *
     * @return array
     */
    public function push($members)
    {
        $script = $this->loadScript('of_capped_priority_queue_push');
        list($len, $out) = $this->runScript($script, $this->composePushArgs($members));
        return [$len, array_chunk($out, 2)];
    }

    /**
     * Push only if the member not already in the queue
     *
     * Returns
     * the queue's length after push, the member(s) been forced out, the success flag
     *
     * @param mixed $member
     * @param mixed $score
     *
     * @return array [int, array, bool]
     */
    public function pushNI($member, $score)
    {
        $script = $this->loadScript('of_capped_priority_queue_push_not_in');
        $raw = $this->runScript($script, $this->composePushArgs([$member => $score]));

        return [$raw[0], array_chunk($raw[1], 2), (bool)$raw[2]];
    }

    /**
     * Push only if the queue not already exist
     * Returns
     * false if the queue already exists
     * else the queue's length after push and the member(s) been forced out
     *
     * @param mixed $members
     *
     * @return bool|array
     */
    public function pushNE($members)
    {
        $script = $this->loadScript('of_capped_priority_queue_push_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return [$raw[0], array_chunk($raw[1], 2)];
    }

    /**
     * Push only if the queue already exists
     * Returns
     * false if the queue not already exist
     * else the queue's length after push and the member(s) been forced out
     *
     * @param mixed $members
     *
     * @return bool|array
     */
    public function pushAE($members)
    {
        $script = $this->loadScript('of_capped_priority_queue_push_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return [$raw[0], array_chunk($raw[1], 2)];
    }
}