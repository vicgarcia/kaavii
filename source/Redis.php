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
     * config array looks like so ...
     *
     *   $config = array(
     *       'scheme'   => 'tcp',
     *       'host'     => <ip or hostname>,
     *       'port'     => <port>,
     *       'database' => <# of db to use>,
     *       'password' => <password>,
     *   );
     *
     *   $config = array(
     *       'scheme'   => 'unix',
     *       'socket'   => <unix socket for redis instance>,
     *       'database' => <# of db to use>,
     *       'password' => <password>,
     *   );
     *
     *
     **/
    public static
        $config = null;

    /**
     * returns a configured \Redis object
     *
     * static config will be used if none provided in constructor
     *
     **/
    public static function connect($config = null)
    {
        if (!empty($config)) {
            $conf = $config;
        } else if (!empty(self::$config)) {
            $conf = self::$config;
        } else {
            // throw exception for no config
            throw new \Exception('no config present for KaaVii\Redis');
        }

        // setup redis client
        $redis = new \Redis;

        if ($conf['scheme'] == 'tcp') {
            $redis->connect($conf['host'], $config['port']);
        } else if ($conf['scheme'] == 'unix') {
            $redis->connect($conf['socket']);
        }

        if (!empty($conf['password'])) {
            $redis->auth($conf['password']);
        }

        if (!empty($conf['database'])) {
            $redis->select($conf['database']);
        }

        return $redis;
    }
}
