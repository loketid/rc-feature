<?php

namespace RCFeature;

class FeatureManager {

    const CONDITION_ENABLED = 1;

    private $features;
    private $dataSource;

    public function __construct(ConnectionDriver $dataSource, array $defaultConfig = null) {
        $this->dataSource = $dataSource;
        $this->fetchConfigurationData();

        if ($this->features == null && $defaultConfig != null) {
            $this->features = $defaultConfig;
        }
    }

    public function isEnabled(string $feature): bool {
        return $this->features[$feature] == self::CONDITION_ENABLED;
    }

    public function getConfiguration(string $feature): string {
        return $this->features[$feature];
    }

    public function enable(string $feature): array {
        $this->dataSource->enable($feature);
        return $this->fetchConfigurationData();
    }

    public function disable(string $feature): array {
        $this->dataSource->disable($feature);
        return $this->fetchConfigurationData();
    }

    public function update(array $config): array {
        $this->dataSource->update($config);
        return $this->fetchConfigurationData();
    }

    private function fetchConfigurationData(): array {
        $this->features = $this->dataSource->fetch();
        return $this->features;
    }
}

?>
