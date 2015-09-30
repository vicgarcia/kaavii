<?php
namespace KaaVii;

class Redis
{
    /**
     * KaaVii\Redis can be configured ...
     *
     *   with a file ...
     *     KaaVii\Redis::$config = require 'config/redis.php';
     *
     *   with an array ...
     *     KaaVii\Redis::$config = [
     *        'sheme' => tcp,
     *        'host' => '127.0.0.1',
     *        'port' => '6379'
     *     ];
     *
     **/
    public static
        $config = null;

    /**
     * returns a configured redis client
     *
     * static config will be used if none provided in constructor params
     *
     * config array looks like so ...
     *
     *     $config = array(
     *         'scheme'   => 'tcp',
     *         'host'     => <ip or hostname>,
     *         'port'     => <port>,
     *         'database' => <# of db to use>,
     *         'password' => <password>,
     *     );
     *
     *     $config = array(
     *         'scheme'   => 'unix',
     *         'socket'   => <unix socket for redis instance>,
     *         'database' => <# of db to use>,
     *         'password' => <password>,
     *     );
     *
     **/
    public static function connect($configParam = null)
    {
        if (!empty($configParam)) {
            $config = $configParam;
        } else if (!empty(self::$config)) {
            $config = self::$config;
        } else {
            // throw exception for no config
            throw new \Exception('No config present for KaaVii\Redis');
        }

        // setup redis client
        $redis = new \Redis;

        if ($config['scheme'] == 'tcp') {
            $redis->connect($config['host'], $config['port']);
        } else if ($config['scheme'] == 'unix') {
            $redis->connect($config['socket']);
        }

        if (!empty($config['password'])) {
            $redis->auth($config['password']);
        }

        if (!empty($config['database'])) {
            $redis->select($config['database']);
        }

        return $redis;
    }
}
