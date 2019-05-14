<?php

namespace RCFeature\Driver;

use Predis\Client;
use RCFeature\DriverManager;

class RedisDriverManager implements DriverManager {

    private $client;
    private $configKey;

    public function __construct(array $config, string $app_name = "rcfeature-client") {
        $this->client = new Client($config);
        $this->configKey = $app_name;
    }

    function fetchAllValue(): array {
        $this->client->hgetall($this->configKey);
    }
}

?>