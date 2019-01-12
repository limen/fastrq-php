<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class Deque
 * @package Limen\Fastrq
 */
class Deque extends Base
{
    /**
     * Deque constructor.
     *
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Push member(s) from the front end
     * Returns
     * the queue's length after push
     *
     * @param $members
     *
     * @return int
     */
    public function pushFront($members)
    {
        $script = $this->loadScript('deque_push_front');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push member(s) from the back end
     * Returns
     * the queue's length after push
     *
     * @param $members
     *
     * @return int
     */
    public function pushBack($members)
    {
        $script = $this->loadScript('deque_push_back');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Pop member(s) from the front end
     * Returns
     * string if count == 1 and the queue is not empty
     * null if count == 1 and the queue is empty
     * else array
     *
     * @param int $count
     *
     * @return string|array|null
     */
    public function popFront($count = 1)
    {
        $script = $this->loadScript('deque_pop_front');
        $pop = $this->runScript($script, $this->composePopArgs($count));
        if ($count === 1) {
            return $pop ? $pop[0] : null;
        }

        return $pop;
    }

    /**
     * Pop member(s) from the front end
     * Returns
     * @see popFront
     *
     * @param int $count
     *
     * @return mixed|null
     */
    public function popBack($count = 1)
    {
        $script = $this->loadScript('deque_pop_back');
        $pop = $this->runScript($script, $this->composePopArgs($count));
        if ($count === 1) {
            return $pop ? $pop[0] : null;
        }

        return $pop;
    }

    /**
     * Push from the front end only if the member not already inside the queue
     * Returns
     * The queue's length and a success flag
     *
     * @param $member
     * @return array [int, bool]
     */
    public function pushFrontNI($member)
    {
        $script = $this->loadScript('deque_push_front_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));
        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push from the back end only if the member not already inside the queue
     * Returns
     * The queue's length and a success flag
     *
     * @param $member
     * @return array [int, bool]
     */
    public function pushBackNI($member)
    {
        $script = $this->loadScript('deque_push_back_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));
        return [$raw[0], (bool)$raw[1]];
    }

    /**
     * Push from front end only if the queue not already exist
     * Returns
     * false if the queue already exists
     * else the queue's length after push
     *
     * @param $members
     * @return bool|int
     */
    public function pushFrontNE($members)
    {
        $script = $this->loadScript('deque_push_front_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push from front end only if the queue not already exist
     * Returns
     * @see pushFrontNE
     *
     * @param $members
     * @return bool|int
     */
    public function pushFrontAE($members)
    {
        $script = $this->loadScript('deque_push_front_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push from back end only if the queue not already exist
     * Returns
     * @see pushFrontNE
     *
     * @param $members
     * @return bool|int
     */
    public function pushBackNE($members)
    {
        $script = $this->loadScript('deque_push_back_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push from back end only if the queue already exists
     * Returns
     * @see pushFrontNE
     *
     * @param $members
     * @return bool|int
     */
    public function pushBackAE($members)
    {
        $script = $this->loadScript('deque_push_back_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return $raw;
    }

    /**
     * @param $start
     * @param $end
     *
     * @return array
     */
    public function range($start, $end)
    {
        return $this->id ? $this->connect()->lrange($this->id, $start, $end) : [];
    }

    /**
     * @return int
     */
    public function length()
    {
        return $this->id ? $this->connect()->llen($this->id) : 0;
    }
}