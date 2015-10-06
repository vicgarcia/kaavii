## KaaVii
KaaVii (pronounced K-V) is a simple library to provide functionality to
interact with Redis for storage and caching.  KaaVii is designed to provide
a very basic set of tools making it perfect for simple applications or rapid
prototyping use.

KaaVii requires PHP >5.4 and the Redis extension.


#### KaaVii\Redis

The KaaVii\Redis class will be your starting point for using KaaVii.  This
class provides factory functionality.  To use it, you provide an array of
configuration data then use the factory method to create a new \Redis client.

While it's certainly possible to use this \Redis object as is to interact with
our Redis server, KaaVii
