<?php

namespace KaaVii;

class RedisTest extends \PHPUnit_Framework_TestCase
{

    public function testErrorWithNoConfig()
    {
        // we expect the exception is thrown, fails if it isn't
        $this->setExpectedException('Kaavii\RedisException');

        // unix config requires 'scheme' in config array
        $redis = Redis::connect([]);
    }

    public function testErrorWithBadConfig()
    {
        // we expect the exception is thrown, fails if it isn't
        $this->setExpectedException('Kaavii\RedisException');

        // value for 'scheme must be either 'tcp' or 'unix'
        $redis = Redis::connect([
            'scheme' => 'other'
        ]);
    }

    public function testErrorWithBadTcpConfig()
    {
        // we expect the exception is thrown, fails if it isn't
        $this->setExpectedException('Kaavii\RedisException');

        // tcp config requires 'host' and 'port' in config array also
        $redis = Redis::connect([
            'scheme' => 'tcp'
        ]);
    }

    public function testErrorWithBadUnixConfig()
    {
        // we expect the exception is thrown, fails if it isn't
        $this->setExpectedException('Kaavii\RedisException');

        // unix config requires 'socket' in config array also
        $redis = Redis::connect([
            'scheme' => 'unix'
        ]);
    }

    /* these tests require a running local redis server */

    /**

    public function testFactoryMethodReturnsRedisObject()
    {
        $redis = Redis::connect([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => '6379',
        ]);

        $this->assertInstanceOf('\Redis', $redis);
    }

    public function testGlobalTcpConfig()
    {
        $tcpConfig = [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => '6379',
            'password' => 'password',
            'database' => '3'
        ];
        Redis::$config = $tcpConfig;

        $redis = Redis::connect();

        $this->assertEquals($redis->GetHost(), '127.0.0.1');
        $this->assertEquals($redis->GetAuth(), 'password');
        $this->assertEquals($redis->GetDBNum(), '3');
    }

    public function testLocalTcpOverridesGlobalConfig()
    {
        $globalConfig = [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => '6379',
            'password' => 'password',
            'database' => '3'
        ];
        Redis::$config = $globalConfig;

        $localConfig = [
            'scheme' => 'tcp',
            'host' => 'localhost',
            'port' => '6379',
            'password' => 'password2',
            'database' => '4'
        ];
        $redis = Redis::connect($localConfig);

        $this->assertEquals($redis->GetHost(), 'localhost');
        $this->assertEquals($redis->GetAuth(), 'password2');
        $this->assertEquals($redis->GetDBNum(), '4');
    }

    **/

}
