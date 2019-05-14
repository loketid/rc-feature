<?php

namespace RCFeature;

class FeatureManager {

    const CONDITION_ENABLED = 1;

    private $features;

    public function __construct(DriverManager $dataSource){
        $this->features = $dataSource->fetchAllValue();
    }

    public function isEnabled(string $feature):bool {
        return $this->features[$feature] == self::CONDITION_ENABLED;
    }
}

?>
