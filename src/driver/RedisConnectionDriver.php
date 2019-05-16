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
        $result = $this->client->hSet($this->configKey, $feature, 0);
        return $result == 1 || $result == 0;
    }

    function enable(string $feature): bool {
        $result = $this->client->hSet($this->configKey, $feature, 1);
        return $result == 1 || $result == 0;
    }

    function update($config): bool {
        return $this->client->hMSet($this->configKey, $config) == 1;
    }
}

?>
