<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Queue with fixed capacity.
 *
 * Class CappedQueue
 *
 * @package Limen\Fastrq
 */
class CappedQueue extends Queue
{
    /**
     * Capacity of the queue
     *
     * @var int
     */
    protected $cap;

    /**
     * CappedQueue constructor.
     *
     * @param null|string $id
     * @param null|int $cap
     */
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

    /**
     * Push member(s)
     * Returns
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return int|string
     */
    public function push($members)
    {
        $script = $this->loadScript('capped_queue_push');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push only if the member not already inside the queue
     * Returns
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push and a success flag
     *
     * @param $member
     *
     * @return array|string
     */
    public function pushNI($member)
    {
        $script = $this->loadScript('capped_queue_push_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));
        if (is_string($raw)) {
            return $raw;
        }

        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push only if the queue already exists
     * Returns
     * @see pushNE
     *
     * @param $members
     *
     * @return bool|string|int
     */
    public function pushAE($members)
    {
        $script = $this->loadScript('capped_queue_push_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw == 'err_ne') {
            return false;
        }

        return $raw;
    }

    /**
     * Push only if the queue not already exist
     * Returns
     * false if the queue already exists
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return bool|int|string
     */
    public function pushNE($members)
    {
        $script = $this->loadScript('capped_queue_push_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw == 'err_ae') {
            return false;
        }

        return $raw;
    }

    protected function composePushArgs($members)
    {
        $args = [1, $this->id, $this->getCap()];
        if (is_array($members)) {
            $args = array_merge($args, $members);
        } else {
            $args[] = $members;
        }

        return $args;
    }
}