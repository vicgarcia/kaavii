<?php
namespace KaaVii;

class StoreTest extends \PHPUnit_Framework_TestCase
{

    public function getRedisMock()
    {
        return $this->getMock('\Redis');
    }

    public function testFormatKeyAndPrefix()
    {
        $redis = $this->getRedisMock();

        $withoutPrefix = new Store($redis, '');
        $this->assertEquals('key', $withoutPrefix->formatKey('key'));

        $withPrefix = new Store($redis, 'prefix');
        $this->assertEquals('prefix:key', $withPrefix->formatKey('key'));
    }

    public function testPrefixHandlesTrailingColon()
    {
        $redis = $this->getRedisMock();

        $withoutColon = new Store($redis, 'prefix');
        $this->assertEquals('prefix:key', $withoutColon->formatKey('key'));

        $withColon = new Store($redis, 'prefix:');
        $this->assertEquals('prefix:key', $withColon->formatKey('key'));
    }

}
