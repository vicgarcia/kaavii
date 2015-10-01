<?php
namespace KaaVii;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testPrefixWithoutTrailingColon()
    {
        $redis = $this->getMock('\Redis');

        $cache = new Cache($redis, 'prefix');
        $this->assertEquals('prefix:key', $cache->formatKey('key'));
    }

    public function testPrefixWithTrailingColon()
    {
        $redis = $this->getMock('\Redis');

        $cache = new Cache($redis, 'prefix:');
        $this->assertEquals('prefix:key', $cache->formatKey('key'));
    }

    public function testFormatKeyWithoutPrefix()
    {
        $redis = $this->getMock('\Redis');

        $cache = new Cache($redis, '');
        $this->assertEquals('key', $cache->formatKey('key'));
    }

    public function testFormatKeyWithPrefix()
    {
        $redis = $this->getMock('\Redis');

        $cache = new Cache($redis, 'prefix');
        $this->assertEquals('prefix:key', $cache->formatKey('key'));
    }

}
