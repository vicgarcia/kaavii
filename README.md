## Kaavii

Kaavii (pronounced kay-vee) is a simple library to provide functionality to
interact with Redis for storage and caching.  Kaavii is designed to provide
a very basic set of tools making it perfect for simple applications or rapid
prototyping use.

Kaavii requires PHP >=5.4 and the Redis extension.


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

    $config = array(
        'scheme'   => 'unix',
        'socket'   => <unix socket for redis instance>,
        'database' => <# of db to use>,
        'password' => <password>,
    );

    // global configuration

    //  with a file ...
    Kaavii\Redis::$config = require 'config/redis.php';

    // with an array ...
    Kaavii\Redis::$config = [
        'sheme' => tcp,
        'host' => '127.0.0.1',
        'port' => '6379'
    ];

    // instance config can be provided as method param

    $redis = Kaavii\Redis::connect($config)


Use the factory method to create a new \Redis client.  The \Redis client is
injected as a dependency in the constructor of other Kaavii components.


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

    // save value to storage

    // keys, delete


### Kaavii\Cache and Kaavii\NoCache

The Kaavii\Cache object is used to provide functionality to cache data to Redis.

The Kaavii\NoCache object is provided for use when you would like to disable
the cache functionality.  When using this object, there is no action taken when
objects are saved to the cache, and objects are never retrieved from the cache.


