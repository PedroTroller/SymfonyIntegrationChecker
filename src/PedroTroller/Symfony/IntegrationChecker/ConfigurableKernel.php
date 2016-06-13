<?php

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
    private $config = array();

    /**
     * @* @var callable[]
     */
    private $afterBoot = array();

    /**
     * @var string
     */
    private $rootDirectory;

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
        return rtrim($this->rootDirectory, '/') . '/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return rtrim($this->rootDirectory, '/') . '/logs';
    }

    /**
     * @param BundleInterface $bundle
     *
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
     * @param ContainerBuilder $containerBuilder
     *
     * @return ConfigurableKernel
     */
    public function setContainerBuilder(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return ConfigurableKernel
     */
    public function afterBoot(callable $callable)
    {
        $this->afterBoot[] = $callable;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAfterBootCallables()
    {
        return $this->afterBoot;
    }

    /**
     * @param array $config
     *
     * @return ConfigurableKernel
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
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
