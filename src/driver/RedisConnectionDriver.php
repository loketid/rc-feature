<?php

namespace RCFeature\Driver;

use Predis\Client;
use RCFeature\ConnectionDriver;

class RedisConnectionDriver implements ConnectionDriver {

    private $client;
    private $configKey;

    public function __construct(array $config, string $app_name = "rc-feature-client") {
        $this->client = new Client($config);
        $this->configKey = $app_name;
    }

    function fetch(): array {
        $this->client->hgetall($this->configKey);
    }

    function disable(string $feature):bool {
        return $this->client->hset($this->configKey, $feature, 0) == 1;
    }

    function enable(string $feature):bool {
        return $this->client->hset($this->configKey, $feature, 1) == 1;
    }

    function update($config): bool {
        return $this->client->hmset($this->configKey, $config) == 1;
    }
}

?>
