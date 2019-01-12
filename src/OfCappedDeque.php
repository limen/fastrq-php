<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Overflow-able capped deque
 *
 * Class OfCappedDeque
 * @package Limen\Fastrq
 */
class OfCappedDeque extends CappedDeque
{
    /**
     * Push member(s) from the front end
     * Returns
     * the queue's length and the member(s) been force out
     *
     * @param mixed $members
     *
     * @return array [int, string[]]
     */
    public function pushFront($members)
    {
        $script = $this->loadScript('of_capped_deque_push_front');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push member(s) from the back end
     * Returns
     * @see pushFront
     *
     * @param mixed $members
     *
     * @return array [int, string[]]
     */
    public function pushBack($members)
    {
        $script = $this->loadScript('of_capped_deque_push_back');
        return $this->runScript($script, $this->composePushArgs($members));
    }

    /**
     * Push from front end only if the member not already in the queue
     *
     * Returns
     * the queue's length after push, the member(s) been forced out, the success flag
     *
     * @param mixed $member
     *
     * @return array [int, string[], bool]
     */
    public function pushFrontNI($member)
    {
        $script = $this->loadScript('of_capped_deque_push_front_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));

        return [$raw[0], $raw[1], (bool)$raw[2]];
    }

    /**
     * Push member(s) from the front end only if the queue not already exist
     * Returns
     * false if the queue already exists
     * the queue's length and the member(s) been force out
     *
     * @param mixed $members
     *
     * @return array|bool [int, string[]]
     */
    public function pushFrontNE($members)
    {
        $script = $this->loadScript('of_capped_deque_push_front_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return $raw;
    }

    /**
     * Push member(s) from the front end only if the queue already exists
     * Returns
     * @see pushFrontNE
     *
     * @param mixed $members
     *
     * @return array|bool [int, string[]]
     */
    public function pushFrontAE($members)
    {
        $script = $this->loadScript('of_capped_deque_push_front_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return $raw;
    }

    /**
     * Push from back end only if the member not already in the queue
     *
     * Returns
     * @see pushFrontNI
     *
     * @param mixed $member
     *
     * @return array [int, string[], bool]
     */
    public function pushBackNI($member)
    {
        $script = $this->loadScript('of_capped_deque_push_back_not_in');
        $raw = $this->runScript($script, $this->composePushArgs($member));

        return [$raw[0], $raw[1], (bool)$raw[2]];
    }

    /**
     * Push member(s) from the back end only if the queue not already exist
     * Returns
     * @see pushFrontNE
     *
     * @param mixed $members
     *
     * @return array|bool [int, string[]]
     */
    public function pushBackNE($members)
    {
        $script = $this->loadScript('of_capped_deque_push_back_ne');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ae') {
            return false;
        }

        return [$raw[0], $raw[1], (bool)$raw[2]];
    }

    /**
     * Push member(s) from the back end only if the queue already exists
     * Returns
     * @see pushFrontNE
     *
     * @param mixed $members
     *
     * @return array|bool [int, string[]]
     */
    public function pushBackAE($members)
    {
        $script = $this->loadScript('of_capped_deque_push_back_ae');
        $raw = $this->runScript($script, $this->composePushArgs($members));
        if ($raw === 'err_ne') {
            return false;
        }

        return [$raw[0], $raw[1], (bool)$raw[2]];
    }
}