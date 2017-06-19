# SYMFONY Integration Checker

## Installation

```bash
$ composer require pedrotroller/symfony-integration-checker --dev ~1.0.0
```

## Usage

You just have to create a `.symfony_checker`. This script should return a callback. This callback will have an instance of `PedroTroller\Symfony\IntegrationChecker\ConfigurableKernel` as parameter.

```php
use PedroTroller\Symfony\IntegrationChecker\ConfigurableKernel;

return function (ConfigurableKernel $kernel) {
    // Your configuration
};
```

## Launch the checker

```bash
$ ./bin/symfony-integration-checker check
```

## Available kernel customization

`$kernel->addBundle(BundleInterface $bundle)`: you can dynamically inject bundles into your kernel.

`$kernel->setConfig(array $config)`: inject a configuration.

`$kernel->setContainerBuilder(ContainerBuilder $container)`: inject your own container.

`$kernel->afterBoot(callable $callable)`: inject a callable. This callable will be executed after the kernel boot.

## Example

```php
<?php

use PedroTroller\Symfony\IntegrationChecker\ConfigurableKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\TranslatorInterface;

$config = array(
    'framework' => array(
        'translator' => array(
            'enabled' => true,
        ),
    ),
);

$test = function (KernelInterface $kernel) {
    if (false === $kernel->getContainer()->get('translator') instanceof TranslatorInterface) {
        throw new \Exception('Oups, there is a problem !');
    }
};

return function (ConfigurableKernel $kernel) use ($config, $test) {
    $container = new ContainerBuilder();
    $container->setParameter('kernel.secret', md5(time()));

    $kernel
        ->setContainerBuilder($container)
        ->setConfig($config)
        ->addBundle(new FrameworkBundle())
        ->afterBoot($test)
    ;
};
```
