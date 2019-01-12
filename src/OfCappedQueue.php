<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class OfCappedQueue
 * @package Limen\Fastrq
 */
class OfCappedQueue extends CappedQueue
{
    /**
     * Push member(s)
     * Returns
     * the queue's length and the member(s) been force out
     *
     * @param mixed $members
     *
     * @return array [int, string[]]
     */
    public function push($members)
    {
        $script = $this->loadScript('of_capped_queue_push');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push only if the member not already in the queue
     *
     * Returns
     * the queue's length after push, the member(s) been forced out, the success flag
     *
     * @param mixed $member
     *
     * @return array [int, string[], bool]
     */
    public function pushNI($member)
    {
        $script = $this->loadScript('of_capped_queue_push_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));

        return [$raw[0], $raw[1], (bool)$raw[2]];
    }

    /**
     * Push member(s) only if the queue not already exist
     * Returns
     * false if the queue already exists
     * the queue's length and the member(s) been force out
     *
     * @param mixed $members
     *
     * @return array|bool [int, string[]]
     */
    public function pushNE($members)
    {
        $script = $this->loadScript('of_capped_queue_push_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push member(s) only if the queue already exists
     * Returns
     * false if the queue not already exist
     * the queue's length and the member(s) been force out
     *
     * @param mixed $members
     *
     * @return array|bool [int, string[]]
     */
    public function pushAE($members)
    {
        $script = $this->loadScript('of_capped_queue_push_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return $raw;
    }
}