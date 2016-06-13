<?php

use Pedrotroller\Symfony\IntegrationChecker\ConfigurableKernel;
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
