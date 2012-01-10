<?php
/**
 * execute command for MyMR.
 *
 * @author Yuya Takeyama
 */
namespace MyMR\Command;

use \Symfony\Component\Console\Command\Command,
    \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Input\InputArgument,
    \Symfony\Component\Console\Input\InputOption,
    \Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('execute')
            ->setDescription('Executes Map/Reduce procedure')
            ->setDefinition(array(
                new InputArgument('file', InputArgument::REQUIRED, 'File defined Map/Reduce procedure definition'),
            ))
            ->addOption('input', 'i', InputOption::VALUE_REQUIRED, 'URI of input table')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'URI of output table');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
