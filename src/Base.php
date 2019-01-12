<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class Base
 *
 * @package Limen\Fastrq
 */
class Base
{
    /**
     * The redis client
     * @var object
     */
    protected $redis;

    /**
     * The queue's ID
     * @var string
     */
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

    public function setClient($client)
    {
        $this->redis = $client;

        return $this;
    }

    public function getClient()
    {
        return $this->redis;
    }

    public function length() {}

    public function range($start, $end) {}

    /**
     * Delete the queue physically
     * @return bool true if successful, false if the queue not exist
     */
    public function destruct()
    {
        return (bool)$this->connect()->del((array)$this->id);
    }

    /**
     * Should return the Redis client
     *
     * @return object
     */
    public function connect()
    {
        if (is_null($this->redis)) {
            $this->redis = new \Predis\Client();
        }

        return $this->redis;
    }

    /**
     * Check if the command script was cached by Redis server
     *
     * @param string $command
     *
     * @return bool
     */
    public function isCommandCached($command)
    {
        $script = $this->loadScript($command);

        return (bool)$this->connect()->script('exists', sha1($script))[0];
    }

    /**
     * Load lua script for the command
     *
     * @param $command
     *
     * @return string
     */
    protected function loadScript($command)
    {
        return Loader::load($command);
    }

    /**
     * Run a Lua script.
     * Try `evalsha` first, fall back to `eval` on error.
     *
     * @param string $script
     * @param array  $params
     *
     * @return mixed
     */
    protected function runScript($script, $params)
    {
        try {
            $sha1 = sha1($script);
            return call_user_func_array([$this->connect(), 'evalsha'], array_merge([$sha1], $params));
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'NOSCRIPT') !== false) {
                return call_user_func_array([$this->connect(), 'eval'], array_merge([$script], $params));
            }
            throw $e;
        }
    }

    /**
     * @param $members
     *
     * @return array
     */
    protected function composePushArgs($members)
    {
        $args = [1, $this->id];
        if (is_array($members)) {
            $args = array_merge($args, $members);
        } else {
            $args[] = $members;
        }

        return $args;
    }

    protected function composePopArgs($count)
    {
        return [1, $this->getId(), $count];
    }

    /**
     * Call Redis client method
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->connect(), $name], $arguments);
    }
}