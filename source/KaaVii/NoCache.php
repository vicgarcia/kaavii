<?php
namespace KaaVii;

class NoCache implements CacheInterface
{

    public function __construct() {}

    public function load($id)
    {
        return false;
    }

    public function save($id, $value, $ttl = 0)
    {
        return $this;
    }

    public function delete($id)
    {
        return $this;
    }

    public function clear()
    {
        return $this;
    }

}
