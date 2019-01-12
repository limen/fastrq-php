<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Deque with fixed capacity.
 *
 * Class CappedDeque
 * @package Limen\Fastrq
 */
class CappedDeque extends Deque
{
    /**
     * Capacity of the queue
     *
     * @var int
     */
    protected $cap;

    /**
     * CappedDeque constructor.
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
     * Push members from the front end
     * Returns
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return int|string
     */
    public function pushFront($members)
    {
        $script = $this->loadScript('capped_deque_push_front');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push members from the back end
     * Returns
     * @see pushFront
     *
     * @param $members
     *
     * @return mixed
     */
    public function pushBack($members)
    {
        $script = $this->loadScript('capped_deque_push_back');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push only if the member not already in the queue
     *
     * Returns
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push and the success flag
     *
     * @param mixed $member
     *
     * @return array|string
     */
    public function pushFrontNI($member)
    {
        $script = $this->loadScript('capped_deque_push_front_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));

        if (is_string($raw)) {
            return $raw;
        }

        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push member(s) from the front end only if the queue not already exist
     * Returns
     * false if the queue already exists
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return bool|mixed
     */
    public function pushFrontNE($members)
    {
        $script = $this->loadScript('capped_deque_push_front_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push member(s) from the front end only if the queue already exists
     * Returns
     * false if the queue not already exist
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return bool|mixed
     */
    public function pushFrontAE($members)
    {
        $script = $this->loadScript('capped_deque_push_front_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return $raw;
    }

    /**
     * Push from back end only if the member not already in the queue
     * Returns
     * @see pushFrontNI
     *
     * @param mixed $member
     *
     * @return array|mixed
     */
    public function pushBackNI($member)
    {
        $script = $this->loadScript('capped_deque_push_back_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));

        if (is_string($raw)) {
            return $raw;
        }

        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push member(s) from the back end only if the queue not already exist
     * Returns
     * false if the queue already exists
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param $members
     *
     * @return bool|mixed
     */
    public function pushBackNE($members)
    {
        $script = $this->loadScript('capped_deque_push_back_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push member(s) from the back end only if the queue already exists
     * Returns
     * false if the queue already exists
     * "err_qf" if the queue is full
     * "err_qof" if the queue is lacked of capacity
     * else the queue's length after push
     *
     * @param mixed $members
     *
     * @return bool|mixed
     */
    public function pushBackAE($members)
    {
        $script = $this->loadScript('capped_deque_push_back_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
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