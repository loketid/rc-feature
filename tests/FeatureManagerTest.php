<?php

namespace RCFeature;

use PHPUnit\Framework\TestCase;

class FeatureManagerTest extends TestCase {
    function testFeatureIsEnabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(DriverManager::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => 1]);

        $instance = new FeatureManager($dataSource);
        $expected = true;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testFeatureIsDisabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(DriverManager::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => 0]);

        $instance = new FeatureManager($dataSource);
        $expected = false;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }
}

?>
