<?php
namespace KaaVii;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testFormatKeyAndPrefix()
    {
        $redis = $this->getMock('\Redis');

        $withoutPrefix = new Cache($redis, '');
        $this->assertEquals('key', $withoutPrefix->formatKey('key'));

        $withPrefix = new Cache($redis, 'prefix');
        $this->assertEquals('prefix:key', $withPrefix->formatKey('key'));
    }

    public function testPrefixHandlesTrailingColon()
    {
        $redis = $this->getMock('\Redis');

        $withoutColon = new Cache($redis, 'prefix');
        $this->assertEquals('prefix:key', $withoutColon->formatKey('key'));

        $withColon = new Cache($redis, 'prefix:');
        $this->assertEquals('prefix:key', $withColon->formatKey('key'));
    }

}
