<?php

namespace KaaVii;


/**
 * the KaaVii\Redis factory can be configured globally/statically ...
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
 * config can be provided on per-instance basis in the factory method
 *
 *   $redis = Kaavii\Redis::connect($config)
 *
 **/
class Redis
{
    public static
        $config = null;

    /**
     * factory method, returns a configured \Redis object
     *
     **/
    public static function connect($config = null)
    {
        // get config to use
        if (!empty($config)) {
            $conf = $config;
        } else if (!empty(self::$config)) {
            $conf = self::$config;
        } else {
            throw new RedisException('no config present for KaaVii\Redis');
        }

        // create \Redis object
        $redis = new \Redis;

        // scheme and config, throw errors as needed
        switch($conf['scheme']) {
            case 'tcp':
                if (!empty($conf['host']) and !empty($conf['port'])) {
                    $redis->connect($conf['host'], $conf['port']);
                } else {
                    throw new RedisException('must provide host/port config');
                }
                break;
            case 'unix':
                if (!empty($conf['socket'])) {
                    $redis->connect($conf['socket']);
                } else {
                    throw new RedisException('must provide socket config');
                }
                break;
            default:
                throw new RedisException('no scheme config, must be tcp/unix');
                break;
        }

        // optional password
        if (!empty($conf['password'])) {
            $redis->auth($conf['password']);
        }

        // optional redis database
        if (!empty($conf['database'])) {
            $redis->select($conf['database']);
        }

        return $redis;
    }
}


/**
 * the Kaavii\RedisException typed exception, used for redis-related errors
 *
 **/
class RedisException extends \Exception
{

}
