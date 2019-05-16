<?php

namespace RCFeature;

class FeatureManager {

    const CONDITION_ENABLED = 1;

    private $features;
    private $dataSource;
    private $defaultConfig;

    public function __construct(ConnectionDriver $dataSource, array $defaultConfig = null) {
        $this->dataSource = $dataSource;
        $this->features = $this->dataSource->fetch();
        $this->defaultConfig = $defaultConfig;

        if (($this->features == null || count($this->features) == 0) && $defaultConfig != null) {
            $this->features = $defaultConfig;
        }
    }

    public function isEnabled(string $feature): bool {
        if (isset($this->features[$feature])) {
            return $this->features[$feature] == self::CONDITION_ENABLED;
        } else {
            return isset($this->defaultConfig[$feature]) ? $this->defaultConfig[$feature] : false;
        }
    }

    public function getRemoteConfiguration(string $feature): ?string {
        if (isset($this->features[$feature])) {
            return $this->features[$feature];
        } else if(isset($this->defaultConfig[$feature])) {
            return $this->defaultConfig[$feature];
        }
        return null;
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
