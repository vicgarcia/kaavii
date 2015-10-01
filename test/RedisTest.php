<?php
namespace KaaVii;

class RedisTest extends \PHPUnit_Framework_TestCase
{

    public function testFactoryMethodReturnsRedisObject()
    {
        $redis = Redis::connect([ 'scheme' => 'tcp' ]);

        $this->assertInstanceOf('\Redis', $redis);
    }

    /* these tests require a running redis server */

    /**

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
