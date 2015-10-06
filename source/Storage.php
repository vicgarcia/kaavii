<?php

namespace KaaVii;

/**
 * the Kaavii\Storage class implements an incredibly simple \Redis
 * backed key-value storage system for serialized PHP values/objects
 *
 **/
class Storage
{
    protected
        $prefix,
        $redis;

    /**
     * provide a configured \Redis object and a prefix string to use
     * for redis keys for the stored values
     *
     **/
    public function __construct(\Redis $redis, $prefix = '')
    {
        $this->redis = $redis;
        $this->prefix = ( (substr($prefix, -1) == ':') or ($prefix == '') )
                ? $prefix : $prefix . ':';
    }

    /**
     *  get the value stored at a key
     *
     *  returns the value
     **/
    public function get($key)
    {
        $serialized = $this->redis->get($this->formatKey($key));

        return (empty($serialized) or ($serialized === false))
                ? false : unserialize($serialized);
    }

    /**
     * set the value stored at a key to value, serializing it
     *
     * returns true on success, false on failure
     **/
    public function set($key, $value)
    {
        return $this->redis->set($this->formatKey($key), serialize($value));
    }

    /**
     * delete value stored at a key
     *
     * returns true on success, false on failure
     **/
    public function delete($key)
    {
        return $this->redis->delete($this->formatKey($key));
    }

    /**
     * retrieve a list of all keys stored, with respect to prefix
     *
     * this will throw an exception if the prefix is empty
     * in order to prevent over-exposing keys
     *
     * returns an array
     **/
    public function keys()
    {
        if (empty($this->prefix)) {
           throw new StorageException('empty prefix w/ clear() delete everything');
        }

        return $this->redis->keys($this->prefix . '*');
    }

    /**
     * formats a string by appending it to the prefix with seperating colon
     * using the prefix allow us to namespace the data stored by Storage in Redis
     *
     * returns a constructed string in the form of 'prefix:key'
     **/
    public function formatKey($key)
    {
        return $this->prefix . $key;
    }
}


/**
 * the Kaavii\StorageException typed exception, used for Kaavii\Storage related errors
 *
 **/
class StorageException extends \Exception
{

}
