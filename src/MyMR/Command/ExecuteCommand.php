<?php
/**
 * execute command for MyMR.
 *
 * @author Yuya Takeyama
 */
namespace MyMR\Command;

use \MyMR\Util,
    \MyMR\Table;

use \Symfony\Component\Console\Command\Command,
    \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Input\InputArgument,
    \Symfony\Component\Console\Input\InputOption,
    \Symfony\Component\Console\Output\OutputInterface;

use PDO;

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
        $inputTable = $this->_createInputTable($input->getOption('input'));
        list($outputTable, $tmpTable) = $this->_createOutputTables($input->getOption('output'));
    }

    protected function _createInputTable($uri)
    {
        $params = Util::parseDatabaseUri($uri);
        return new Table($params['table'], $this->_createPdo($params));
    }

    protected function _createOutputTables($uri)
    {
        $params = Util::parseDatabaseUri($uri);
        $pdo = $this->_createPdo($params);
        return array(
            new Table($params['table'], $pdo),
            new Table('job', $pdo)
        );
    }

    protected function _createPdo($params)
    {
    
        $dsn = "mysql:dbname={$params['database']};" .
            "host={$params['host']};" .
            "port={$params['port']}";
        return new PDO($dsn, $params['user'], $params['pass']);
    }
}
