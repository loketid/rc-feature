<?php

namespace RCFeature;

class FeatureManager {

    const CONDITION_ENABLED = 1;

    private $features;

    public function __construct($dataSource){
        $this->features = $dataSource->fetchAllValue();
    }

    public function isEnabled($feature) {
        return $this->features[$feature] == self::CONDITION_ENABLED;
    }
}

?>
