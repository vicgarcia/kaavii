<?php
namespace KaaVii;

interface CacheInterface
{
    /*
     * @param string $id
     * @return multi|false  value from cache or false if none
     */
    public function load($id);

    /*
     * @param string $id
     * @param multi $value
     * @param int $ttl  time in seconds cache will exist
     * @return self
     */
    public function save($id, $value, $ttl);

    /*
     * @param string $id
     * @return self
     */
    public function delete($id);

    /*
     * Remove (delete) all values stored in the cache
     * @return self
     */
    public function clear();

}
