<?php

namespace RCFeature\Driver;

use RCFeature\ConnectionDriver;
use Redis;

class RedisConnectionDriver implements ConnectionDriver {
    private $client;
    private $configKey;

    public function __construct(array $config, string $app_name = "rc-feature-client") {
        $this->client = new Redis();
        $this->client->connect($config['hostname'], $config['port'], $config['timeout']);
        $this->configKey = $app_name;
    }

    function fetch(): array {
        return $this->client->hGetAll($this->configKey);
    }

    function disable(string $feature): bool {
        return $this->client->hSet($this->configKey, $feature, 0) == 1;
    }

    function enable(string $feature): bool {
        return $this->client->hSet($this->configKey, $feature, 1) == 1;
    }

    function update($config): bool {
        return $this->client->hMSet($this->configKey, $config) == 1;
    }
}

?>
