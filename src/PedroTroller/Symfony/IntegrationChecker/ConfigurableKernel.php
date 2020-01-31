<?php

declare(strict_types=1);

namespace PedroTroller\Symfony\IntegrationChecker;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

class ConfigurableKernel extends Kernel
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @* @var callable[]
     */
    private $afterBoot = [];

    /**
     * @var string
     */
    private $rootDirectory;

    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);

        $this->rootDirectory = sprintf('%s/../../../cache', __DIR__);
    }

    /**
     * @return ConfigurableKernel
     */
    public function setContainerBuilder(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;

        return $this;
    }

    /**
     * @return ConfigurableKernel
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return ConfigurableKernel
     */
    public function afterBoot(callable $callable)
    {
        $this->afterBoot[] = $callable;

        return $this;
    }

    /**
     * @param string $rootDirectory
     *
     * @return ConfigurableKernel
     */
    public function setRootDirectory($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return rtrim($this->rootDirectory, '/').'/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return rtrim($this->rootDirectory, '/').'/logs';
    }

    /**
     * @return ConfigurableKernel
     */
    public function addBundle(BundleInterface $bundle)
    {
        $this->registeredBundles[] = $bundle;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return $this->registeredBundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        return $this->getContainerBuilder();
    }

    /**
     * @return callable[]
     */
    public function getAfterBootCallables()
    {
        return $this->afterBoot;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerBuilder()
    {
        if (null === $this->containerBuilder) {
            $this->containerBuilder = parent::getContainerBuilder();
        } else {
            $this->containerBuilder->merge(parent::getContainerBuilder());
        }

        foreach ($this->config as $extension => $config) {
            $this->containerBuilder->prependExtensionConfig($extension, $config);
        }

        return $this->containerBuilder;
    }
}
