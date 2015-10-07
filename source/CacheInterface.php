<?php

namespace Kaavii;


/**
 * the Kaavii\CacheInterface defines the functionality that is expected
 * from cache implementations
 *
 **/
interface CacheInterface
{
    public function load($key);

    public function save($key, $value, $ttl);

    public function delete($key);

    public function clear();
}


/**
 * the Kaavii\CacheException typed exception, used for cache-related errors
 *
 **/
class CacheException extends \Exception
{

}

