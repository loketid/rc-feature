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

    public function enable(string $feature) {
        $this->dataSource->enable($feature);
        $this->fetchConfigurationData();
    }

    public function disable(string $feature) {
        $this->dataSource->disable($feature);
        $this->fetchConfigurationData();
    }

    private function fetchConfigurationData() {
        $this->features = $this->dataSource->fetchAllValue();
    }
}

?>
