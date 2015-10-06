<?php

namespace Kaavii;

class StorageTest extends \PHPUnit_Framework_TestCase
{

    public function testPrefixWithoutTrailingColon()
    {
        $storage = new Storage($this->getMock('\Redis'), 'prefix');

        $this->assertEquals('prefix:key', $storage->formatKey('key'));
    }

    public function testPrefixWithTrailingColon()
    {
        $storage = new Storage($this->getMock('\Redis'), 'prefix:');

        $this->assertEquals('prefix:key', $storage->formatKey('key'));
    }

    public function testFormatKeyWithoutPrefix()
    {
        $storage = new Storage($this->getMock('\Redis'), '');

        $this->assertEquals('key', $storage->formatKey('key'));
    }

    public function testFormatKeyWithPrefix()
    {
        $storage = new Storage($this->getMock('\Redis'), 'prefix');

        $this->assertEquals('prefix:key', $storage->formatKey('key'));
    }

    public function testGetStringValueAtKey()
    {
        $expectedString = 'expected cache content';
        $serializedString = serialize($expectedString);

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->method('get')->willReturn($serializedString);

        $storage = new Storage($redis, 'prefix');
        $this->assertEquals($expectedString, $storage->get('unchecked:key'));
    }

    public function testLoadArrayValueAtKey()
    {
        $expectedArray = [ 'an', 'array', 'value' ];
        $serializedArray = serialize($expectedArray);

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->method('get')->willReturn($serializedArray);

        $storage = new Storage($redis, 'prefix');
        $this->assertEquals($expectedArray, $storage->get('unchecked:key'));
    }

    public function testLoadInvalidValueAtKey()
    {
        $redis1 = $this->getMockBuilder('\Redis')->getMock();
        $redis1->method('get')->willReturn(false);

        $storage = new Storage($redis1, 'prefix');
        $this->assertEquals(false, $storage->get('unchecked:key'));

        $redis2 = $this->getMockBuilder('\Redis')->getMock();
        $redis2->method('get')->willReturn([]);

        $storage = new Storage($redis2, 'prefix');
        $this->assertEquals(false, $storage->get('unchecked:key'));
    }

    public function testSet()
    {
        $value = 'a string value';

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('set')
            ->with('prefix:key', serialize($value))
            ->willReturn(true);

        $storage = new Storage($redis, 'prefix');

        $this->assertTrue($storage->set('key', $value));
    }

    public function testDeleteCallsRedisDeleteMethod()
    {
        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('delete')
            ->with('prefix:key')
            ->willReturn(true);

        $storage = new Storage($redis, 'prefix');

        $this->assertTrue($storage->delete('key'));
    }

    public function testKeysGetsArrayFromRedis()
    {
        $expectedArray = [ 'an', 'array', 'value' ];

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('keys')
            ->with('prefix:*')
            ->willReturn($expectedArray);

        $storage = new Storage($redis, 'prefix');

        $this->assertEquals($expectedArray, $storage->keys());
    }

    public function testKeysErrorWithEmptyPrefix()
    {
        $redis = $this->getMockBuilder('\Redis')->getMock();

        // we expect the exception is thrown, fails if it isn't
        $this->setExpectedException('Kaavii\StorageException');

        $storage = new Storage($redis, '');
        $keys = $storage->keys();
    }

}
