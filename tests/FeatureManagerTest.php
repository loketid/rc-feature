<?php

namespace RCFeature;

use PHPUnit\Framework\TestCase;

class FeatureManagerTest extends TestCase {
    function testFeatureIsEnabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => 1]);

        $instance = new FeatureManager($dataSource);
        $expected = true;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testFeatureIsDisabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => 0]);

        $instance = new FeatureManager($dataSource);
        $expected = false;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testGetConfigurationFromFeature() {
        $featureName = "feature-1";
        $featureConfig = "new-relic";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => $featureConfig]);

        $instance = new FeatureManager($dataSource);
        $expected = $featureConfig;

        $this->assertEquals($expected, $instance->getConfiguration($featureName));
    }

    function testUpdateAllConfiguration() {
        $featureName = "feature-1";
        $featureConfig = "new-relic";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('updateAllValue')->willReturn(true);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => $featureConfig]);

        $instance = new FeatureManager($dataSource);
        $expected = [$featureName => $featureConfig];

        $this->assertEquals($expected, $instance->updateAll([$featureName => $featureConfig]));
    }
}

?>
