<?php

namespace KaaVii;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testPrefixWithoutTrailingColon()
    {
        $cache = new Cache($this->getMock('\Redis'), 'prefix');

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

    public function testLoadStringValueAtKey()
    {
        $expectedString = 'expected cache content';
        $serializedString = serialize($expectedString);

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->method('get')->willReturn($serializedString);

        $cache = new Cache($redis, 'prefix');
        $this->assertEquals($expectedString, $cache->load('unchecked:key'));
    }

    public function testLoadArrayValueAtKey()
    {
        $expectedArray = [ 'an', 'array', 'value' ];
        $serializedArray = serialize($expectedArray);

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->method('get')->willReturn($serializedArray);

        $cache = new Cache($redis, 'prefix');
        $this->assertEquals($expectedArray, $cache->load('unchecked:key'));
    }

    public function testLoadInvalidValueAtKey()
    {
        $redis1 = $this->getMockBuilder('\Redis')->getMock();
        $redis1->method('get')->willReturn(false);

        $cache1 = new Cache($redis1, 'prefix');
        $this->assertEquals(false, $cache1->load('unchecked:key'));

        $redis2 = $this->getMockBuilder('\Redis')->getMock();
        $redis2->method('get')->willReturn([]);

        $cache2 = new Cache($redis1, 'prefix');
        $this->assertEquals(false, $cache2->load('unchecked:key'));
    }

    public function testSaveWithoutTTL()
    {
        $value = 'a string value';

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('set')
            ->with('prefix:key', serialize($value))
            ->willReturn(true);

        $cache = new Cache($redis, 'prefix');

        $this->assertTrue($cache->save('key', $value));
    }

    public function testSaveWithTTL()
    {
        $value = 'a string value';

        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('setex')
            ->with('prefix:key', 200, serialize($value))
            ->willReturn(true);

        $cache = new Cache($redis, 'prefix');

        $this->assertTrue($cache->save('key', $value, 200));
    }

    public function testDeleteCallsRedisDeleteMethod()
    {
        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('delete')
            ->with('prefix:key')
            ->willReturn(true);

        $cache = new Cache($redis, 'prefix');

        $this->assertTrue($cache->delete('key'));
    }

    public function testClearErrorWithEmptyPrefix()
    {
        $redis = $this->getMockBuilder('\Redis')->getMock();

        // we expect the exception is thrown, fails if it isn't
        $this->setExpectedException('Kaavii\CacheException');

        $cache = new Cache($redis, '');
        $deleted = $cache->clear();
    }

    public function testClearDeleteCountMatchesKeysReturned()
    {
        $redis = $this->getMockBuilder('\Redis')->getMock();
        $redis->expects($this->once())
            ->method('keys')
            ->with('prefix:*')
            ->willReturn(['one', 'two', 'three']);
        $redis->expects($this->exactly(3))
            ->method('del')
            ->willReturn(true);

        $cache = new Cache($redis, 'prefix');

        $this->assertEquals(3, $cache->clear());
    }

}
