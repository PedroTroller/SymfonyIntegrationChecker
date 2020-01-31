<?php

declare(strict_types=1);

namespace PedroTroller\Symfony\IntegrationChecker;

use PedroTroller\Symfony\IntegrationChecker\Command\CheckCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): void
    {
        $input  = null !== $input ? $input : new ArgvInput();
        $output = null !== $output ? $output : new ConsoleOutput();

        $this->add(new CheckCommand());

        $this->configureIO($input, $output);

        parent::run($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultHelperSet()
    {
        return new HelperSet([]);
    }
}
