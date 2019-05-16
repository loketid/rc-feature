# RC Feature

RC Feature (Remote Configuration Feature) is a dynamic configuration library for 
feature toggle and configuration fetch from remote server.

## Dependencies

- PHP 7.2
- PHPUnit 8.1.4

## How to Test

Run ```phpunit``` inside project folder.

## How to Use

#### Constructor

To create a new instance object, you can use these constructor:

```php
namespace RCFeature

$featureManager = new FeatureManager(ConnectionDriver, DefaultConfig);
```

- ConnectionDriver is object that we use to persist storage
- DefaultConfig is associative array that store default configuration if any feature config not found on persistence storage
```php
$config = [
   "feature-1" => 1, // 1 represent enabled feature
   "feature-2" => 0, // 0 represent disabled feature
   "feature-config" => "some string"
]
```

#### isEnabled

Used to check whether feature is enabled.

```php
$featureManager->isEnabled("feature-1"); // return true or false
```

#### getRemoteConfiguration

Used to get configuration that stored in persistence.
```php
$featureManager->getRemoteConfiguration("feature-config"); // return string
```

#### enable

Used to enable specific feature.

```php
$featureManager->enable("feature-1"); // return true if success
```

#### disable

Used to disable specific feature.

```php
$featureManager->disable("feature-1"); // return true if success
```

#### update

Used to update multiple feature configuration / state at same time.

```php
$featureConfig = [
   "feature-1" => 1,
   "feature-2" => 0,
   "feature-config" => "some string",
]

$featureManager->update($featureConfig); // return true if success
```

### Connection Driver

As mentioned above, this library need connection driver to passed within constructor.

Currently support redis, using phpredis extension.

#### RedisConnectionDriver

```php
use RCFeature/Driver;

$driverConfig = [
    "host" => "127.0.0.1",
    "port" => "4567",
    "timeout" => 1000
]

$appName = "your-app-name"

$driver = new RedisConnectionDriver($driverConfig, $appName);
```

and pass the driver object to feature manager constructor.

```php
$instance = new FeatureManager($driver, $defaultConfig);
```
