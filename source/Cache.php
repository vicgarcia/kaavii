<?php

namespace KaaVii;

/**
 * the Kaavii\Cache class implements Kaavii\CacheInterface using \Redis
 * to provide an implementation for the cache functionality
 *
 **/
class Cache implements CacheInterface
{
    protected
        $prefix,
        $redis;

    /**
     * provide a configured \Redis object and a prefix string to use
     * for redis keys for the cached values
     *
     **/
    public function __construct(\Redis $redis, $prefix = 'cache:')
    {
        $this->redis = $redis;
        $this->prefix = ( (substr($prefix, -1) == ':') or ($prefix == '') )
                ? $prefix : $prefix . ':';
    }

    /**
     * load value from cache w/ key
     *
     * returns the value
     **/
    public function load($key)
    {
        $cached = $this->redis->get($this->formatKey($key));

        return (empty($cached) or ($cached === false))
                ? false : unserialize($cached);
    }

    /**
     * save value to cache w/ key, optional expiration (ttl in seconds)
     *
     * returns true on success, false on failure
     **/
    public function save($key, $value, $ttl = null)
    {
        $formattedKey = $this->formatKey($key);
        $serializedValue = serialize($value);

        if (is_int($ttl) and ($ttl != 0)) {
            return $this->redis->setex($formattedKey, $ttl, $serializedValue);
        } else {
            return $this->redis->set($formattedKey, $serializedValue);
        }
    }

    /**
     * delete value from cache w/ key
     *
     * returns true on success, false on failure
     **/
    public function delete($key)
    {
        return $this->redis->delete($this->formatKey($key));
    }

    /**
     * delete all cached values
     *
     * this will throw an exception if the prefix is empty
     * in order to prevent deleting all items in redis
     *
     * returns count of the number of deleted cache items
     **/
    public function clear()
    {
        if (empty($this->prefix)) {
           throw new CacheException('empty prefix w/ clear() will delete everything');
        }

        $deleted = 0;
        foreach ($this->redis->keys($this->prefix . '*') as $key) {
            $this->redis->del($key);
            $deleted++;
        }

        return $deleted;
    }

    /**
     * formats a string by appending it to the prefix for use as a cache key
     *
     * returns a constructed string in the form of 'prefix:key'
     **/
    public function formatKey($id)
    {
        return $this->prefix . $id;
    }

}
