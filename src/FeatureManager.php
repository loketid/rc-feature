<?php

namespace RCFeature;

class FeatureManager {

    const CONDITION_ENABLED = 1;

    private $features;
    private $dataSource;

    public function __construct(ConnectionDriver $dataSource, array $defaultConfig = null) {
        $this->dataSource = $dataSource;
        $this->features = $this->dataSource->fetch();

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

    public function enable(string $feature): bool {
        if ($this->dataSource->enable($feature)) {
            $this->features = $this->dataSource->fetch();
            return true;
        }
        return false;
    }

    public function disable(string $feature): bool {
        if ($this->dataSource->disable($feature)) {
            $this->features = $this->dataSource->fetch();
            return true;
        }
        return false;
    }

    public function update(array $config): bool {
        if ($this->dataSource->update($config)) {
            $this->features = $this->dataSource->fetch();
            return true;
        }
        return false;
    }
}

?>
