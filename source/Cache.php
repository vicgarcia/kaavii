<?php
namespace KaaVii;

class Cache implements CacheInterface
{
    protected
        $prefix,
        $redis;

    public function __construct(\Redis $redis, $prefix = 'cache:')
    {
        $this->redis = $redis;
        $this->prefix = ( (substr($prefix, -1) == ':') or ($prefix == '') )
                ? $prefix : $prefix . ':';
    }

    public function load($key)
    {
        $cached = $this->redis->get($this->formatKey($key));

        return (empty($cached) or ($cached === false))
                ? false : unserialize($cached);
    }

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

    public function delete($key)
    {
        return $this->redis->delete($this->formatKey($key));
    }

    public function clear()
    {
        $deleted = 0;
        foreach ($this->redis->keys($this->prefix . '*') as $key) {
            $this->redis->del($key);
            $deleted++;
        }

        return $deleted;
    }

    public function formatKey($id)
    {
        return $this->prefix . $id;
    }

}
