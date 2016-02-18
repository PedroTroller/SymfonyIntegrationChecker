<?php

namespace Pedrotroller\Symfony\IntegrationChecker\Command;

use Pedrotroller\Symfony\IntegrationChecker\ConfigurableKernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('check')
            ->addArgument('bootstrap_file', InputArgument::OPTIONAL, 'The kernel initialisation file.', $this->getDefaultBootstrapFilename())
            ->addOption('env', 'e', InputOption::VALUE_REQUIRED, 'Symfony environement', 'prod')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $file = $this->getDefaultBootstrapFilename();
        $env  = 'prod';

        if (true === $input->hasOption('env')) {
            $env = $input->getOption('env');
        }

        if (true === $input->hasArgument('bootstrap_file')) {
            $file = $input->getArgument('bootstrap_file');
        }

        if (false === file_exists($file)) {
            throw new \Exception(sprintf('Bootstrap file "%s" not found', $file));
        }

        $kernel   = new ConfigurableKernel($env, true);
        $callback = require $file;

        $callback($kernel);

        $output->writeln('<info>Kernel builded</info>');

        $kernel->boot();

        $output->writeln('<info>Kernel booted</info>');

        foreach ($kernel->getAfterBootCallables() as $callable) {
            $callable($kernel);
        }

        $output->writeln('<info>Symfony integration succeeded</info>');
    }

    /**
     * @return string
     */
    private function getDefaultBootstrapFilename()
    {
        return sprintf('%s/checker_bootstrap.php', getcwd());
    }
}
