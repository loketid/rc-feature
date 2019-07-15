<?php

namespace RCFeature;

use PHPUnit\Framework\TestCase;
use RCFeature\Driver\ConnectionDriver;

class FeatureManagerTest extends TestCase {
    const CONDITION_ENABLED = 1;
    const CONDITION_DISABLED = 0;

    function testFeatureIsEnabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')->willReturn([$featureName => self::CONDITION_ENABLED]);

        $instance = new FeatureManager($dataSource);
        $expected = true;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testFeatureIsEnabledWithDefaultValue() {
        $featureName = "feature-1";

        $dataSource = $this->createMock(ConnectionDriver::class);

        $instance = new FeatureManager($dataSource, [$featureName => self::CONDITION_ENABLED]);
        $expected = true;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testFeatureIsDisabledWithUndefinedKey() {
        $featureName = "feature-1";
        $anotherFeatureName = "feature-2";

        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')->willReturn([$featureName => self::CONDITION_ENABLED]);

        $instance = new FeatureManager($dataSource);
        $expected = false;

        $this->assertEquals($expected, $instance->isEnabled($anotherFeatureName));
    }

    function testFeatureIsDisabled() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')->willReturn([$featureName => self::CONDITION_DISABLED]);

        $instance = new FeatureManager($dataSource);
        $expected = false;

        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testGetConfigurationFromFeature() {
        $featureName = "feature-1";
        $featureConfig = "new-relic";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')->willReturn([$featureName => $featureConfig]);

        $instance = new FeatureManager($dataSource);
        $expected = $featureConfig;

        $this->assertEquals($expected, $instance->getRemoteConfiguration($featureName));
    }

    function testGetConfigurationFromFeatureWithDefaultValue() {
        $featureName = "feature-1";
        $featureConfig = "new-relic";
        $anotherFeatureName = "feature-2";
        $anotherFeatureConfig = "data-dog";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')->willReturn([$featureName => $featureConfig]);

        $instance = new FeatureManager($dataSource, [$anotherFeatureName => $anotherFeatureConfig]);
        $expected = $anotherFeatureConfig;

        $this->assertEquals($expected, $instance->getRemoteConfiguration($anotherFeatureName));
    }

    function testGetConfigurationFromFeatureWithUndefinedKey() {
        $featureName = "feature-1";
        $featureConfig = "new-relic";
        $undefinedFeatureName = "feature-2";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')->willReturn([$featureName => $featureConfig]);

        $instance = new FeatureManager($dataSource);
        $expected = null;

        $this->assertEquals($expected, $instance->getRemoteConfiguration($undefinedFeatureName));
    }

    function testUpdateAllConfiguration() {
        $featureName = "feature-1";
        $featureConfig = "new-relic";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('update')->willReturn(true);
        $dataSource->method('fetch')->willReturn([$featureName => $featureConfig]);

        $instance = new FeatureManager($dataSource);
        $expected = $featureConfig;

        $instance->update([$featureName => $featureConfig]);

        $this->assertEquals($expected, $instance->getRemoteConfiguration($featureName));
    }

    function testEnableFeature() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')
            ->will($this->onConsecutiveCalls([$featureName => self::CONDITION_DISABLED], [$featureName => self::CONDITION_ENABLED]));
        $dataSource->method('enable')->willReturn(true);

        $instance = new FeatureManager($dataSource);

        $initialState = false;
        $this->assertEquals($initialState, $instance->isEnabled($featureName));
        $instance->enable($featureName);

        $expected = true;
        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }

    function testDisableFeature() {
        $featureName = "feature-1";
        $dataSource = $this->createMock(ConnectionDriver::class);
        $dataSource->method('fetch')
            ->will($this->onConsecutiveCalls([$featureName => self::CONDITION_ENABLED], [$featureName => self::CONDITION_DISABLED]));
        $dataSource->method('disable')->willReturn(true);

        $instance = new FeatureManager($dataSource);

        $initialState = true;
        $this->assertEquals($initialState, $instance->isEnabled($featureName));
        $instance->disable($featureName);

        $expected = false;
        $this->assertEquals($expected, $instance->isEnabled($featureName));
    }
}

?>
