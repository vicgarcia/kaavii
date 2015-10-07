## Kaavii

Kaavii (pronounced kay-vee) is a simple library to provide functionality to
interact with Redis for cache and key-value storage.  Kaavii is designed to
provide a very basic set of tools making it perfect for simple applications
or rapid prototyping use.

Kaavii requires PHP >=5.4 and the Redis extension.

I create Kaavii because I like using Redis.  Cache and simple storage are
the two cases I use Redis for most often, and I wanted a consistent way to
get set up and using Redis for these.

Add this to your composer.json's require section and update Composer

    "vicgarcia/kaavii": "dev-dev",


### Kaavii\Redis

The Kaavii\Redis class will be your starting point for using Kaavii.  This
class provides a sort factory functionality for creating configured \Redis
objects.

To use it, you provide an array of configuration data, either as a global
configuration or as a parameter to the factory method call.

    // config array looks like so ...
    $config = array(
        'scheme'   => 'tcp',
        'host'     => <ip or hostname>,
        'port'     => <port>,
        'database' => <# of db to use>,
        'password' => <password>,
    );
    // nb: see code for use w/ unix socket

    // using Kaavii global configuration for Redis

    // with a file ...
    Kaavii\Redis::$config = require 'config/redis.php';

    // with an array ...
    Kaavii\Redis::$config = [
        'sheme' => tcp,
        'host' => '127.0.0.1',
        'port' => '6379'
    ];

    // getting a redis client object
    $redis = Kaavii\Redis::connect()

    // per-instance config can be provided as method param
    $redis = Kaavii\Redis::connect($config)


Use the factory method to create a new \Redis client.  The \Redis client is
injected as a dependency in the constructor of other Kaavii components.

### Kaavii\Cache and Kaavii\NoCache

The Kaavii\Cache object is used to provide functionality to cache data to Redis.

    // configure redis and connect
    Kaavii\Redis::$config = require 'config/redis.php';
    $redis = Kaavii\Redis::connect();

    // a prefix is prepended to keys, seperated by a colon
    // this type of namespacing is convention with Redis
    $prefix = 'cache';

    // create cache object
    $cache = Kaavii\Cache($redis, $prefix);

    // a simple cache block
    if ( ($lifestream = $cache->load('lifestream')) === false ) {
        $lifestream = (new Lifestream())->getCurrent();
        $cache->save('lifestream', $lifestream, 14400);
    }

    // delete a cached value by key
    $cache->delete('lifestream');

    // clear all cached values
    $cache->clear();


The Kaavii\NoCache object is provided for use when you would like to disable
the cache functionality.  When using this object, there is no action taken when
objects are saved to the cache, and objects are never retrieved from the cache.

    // a NoCache object needs no Redis client
    $cache = new Kaavii\NoCache;

    // with nocache, the code within the block will always execute
    if ( ($weatherchart = $cache->load('weatherchart')) === false ) {
        $weatherchart = (new WeatherChart())->getDaily();

        // with nocache, nothing is 'saved', the method simply returns
        $cache->save('weatherchart', $weatherchart, (24 * 60 * 60);
    }


### Kaavii\Storage

The Kaavii\Storage component provides a simple way to use Redis as a key value
storage.  Handling for keys and serialization of the value data is also
available.

    // configure redis and connect
    Kaavii\Redis::$config = require 'config/redis.php';
    $redis = Kaavii\Redis::connect();

    // create storage object
    $storage = Kaavii\Storage($redis, 'prefix');

    // get a value from storage
    $value = $storage->get('key');

    // save value to storage
    $storage->set('key', $value);

    // get all keys
    $keys = $storage->keys();

    // delete a key
    $storage->delete('key');

