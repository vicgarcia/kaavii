<?php

namespace KaaVii;


/**
 * the Kaavii\NoCache class provides a sort of 'stub' to use in place of
 * a configured Kaavii\Cache object in order to disable caching
 *
 **/
class NoCache implements CacheInterface
{
    public function __construct() {}

    /**
     * always return false, which means no item/data found in cache
     *
     * this will force all calls that check for cache to perform full operation
     **/
    public function load($id)
    {
        return false;
    }

    /**
     * return true to indicate the the operation succeded
     *
     **/
    public function save($id, $value, $ttl = 0)
    {
        return true;
    }

    /**
     * return true to indicate the the operation succeded
     *
     **/
    public function delete($id)
    {
        return true;
    }

    /**
     * always return 0, indicating that no items were deleted from cache
     *
     **/
    public function clear()
    {
        return 0;
    }

}
