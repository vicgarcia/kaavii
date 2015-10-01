<?php
namespace KaaVii;

class Store
{
    protected
        $prefix,
        $redis;

    public function __construct(\Redis $redis, $prefix = '')
    {
        $this->redis = $redis;
        $this->prefix = ( (substr($prefix, -1) == ':') or ($prefix == '') )
                ? $prefix : $prefix . ':';
    }

    public function get($key)
    {
        $serialized = $this->redis->get($this->formatKey($key));

        return (empty($serialized) or ($serialized === false))
                ? false : unserialize($serialized);
    }

    public function set($key, $value)
    {
        $formattedKey = $this->formatKey($key);
        $serialized = serialize($value);

        return $this->redis->set($formattedKey, $serialized);
    }

    public function delete($key)
    {
        return $this->redis->delete($this->formatKey($key));
    }

    public function keys()
    {
        return $this->redis->keys($this->prefix . '*');
    }

    public function formatKey($key)
    {
        return $this->prefix . $key;
    }
}
