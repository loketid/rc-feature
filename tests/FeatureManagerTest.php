<?php

namespace RCFeature;

use PHPUnit\Framework\TestCase;

class FeatureManagerTest extends TestCase {
    const CONDITION_ENABLED = 1;
    const CONDITION_DISABLED = 0;

    function testFeatureIsEnabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => self::CONDITION_ENABLED]);

        $instance = new FeatureManager($dataSource);
        $expected = true;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testFeatureIsDisabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')->willReturn([$featureName => self::CONDITION_DISABLED]);

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

    function testEnableFeature() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')
            ->will($this->onConsecutiveCalls([$featureName => self::CONDITION_DISABLED], [$featureName => self::CONDITION_ENABLED]));
        $dataSource->method('enable')->willReturn(true);

        $instance = new FeatureManager($dataSource);

        $initialState = false;

        $this->assertEquals($initialState, $instance->isEnabled($featureName));

        $instance = new FeatureManager($dataSource);

        $expected = true;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testDisableFeature() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetchAllValue')
            ->will($this->onConsecutiveCalls([$featureName => self::CONDITION_ENABLED], [$featureName => self::CONDITION_DISABLED]));
        $dataSource->method('disable')->willReturn(true);

        $instance = new FeatureManager($dataSource);

        $initialState = true;

        $this->assertEquals($initialState, $instance->isEnabled($featureName));

        $instance = new FeatureManager($dataSource);

        $expected = false;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }
}

?>
