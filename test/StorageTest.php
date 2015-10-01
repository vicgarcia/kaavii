<?php
namespace KaaVii;

class StorageTest extends \PHPUnit_Framework_TestCase
{

    public function testFormatKeyWithoutPrefix()
    {
        $redis = $this->getMock('\Redis');

        $storage = new Storage($redis, '');
        $this->assertEquals('key', $storage->formatKey('key'));
    }

    public function testFormatKeyWithPrefix()
    {
        $redis = $this->getMock('\Redis');

        $storage = new Storage($redis, 'prefix');
        $this->assertEquals('prefix:key', $storage->formatKey('key'));
    }

    public function testPrefixWithoutTrailingColon()
    {
        $redis = $this->getMock('\Redis');

        $storage = new Storage($redis, 'prefix');
        $this->assertEquals('prefix:key', $storage->formatKey('key'));
    }

    public function testPrefixWithTrailingColon()
    {
        $redis = $this->getMock('\Redis');

        $storage = new Storage($redis, 'prefix:');
        $this->assertEquals('prefix:key', $storage->formatKey('key'));
    }

}
