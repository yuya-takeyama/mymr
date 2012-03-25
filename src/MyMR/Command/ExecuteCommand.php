<?php
/**
 * execute command for MyMR.
 *
 * @author Yuya Takeyama
 */
namespace MyMR\Command;

use \MyMR\Util,
    \MyMR\Database,
    \MyMR\Table;

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

    /**
     * Main procedure.
     *
     * @param  InputInterface   $input
     * @param  OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $inputTable = $this->_createInputTable($input->getOption('input'));
        list($outputTable, $tmpTable) = $this->_createOutputTables($input->getOption('output'));
        $file = $input->getArgument('file');
        require_once $file;
        preg_match('#([^/]+)\.php$#', $file, $matches);
        $klass = "\\{$matches[1]}";
        $procedure = new $klass;
        $procedure->execute($inputTable, $outputTable, $tmpTable, $output);
    }

    /**
     * Constructs input Table object.
     *
     * @param  string $uri
     * @return Table
     */
    protected function _createInputTable($uri)
    {
        $params = Util::parseDatabaseUri($uri);
        $database = $this->_createDatabase($params);
        return $database->getTable($params['table']);
    }

    /**
     * Constructs output Table and tmp Table objects.
     *
     * @param  string $uri
     * @return array
     */
    protected function _createOutputTables($uri)
    {
        $params = Util::parseDatabaseUri($uri);
        $database = $this->_createDatabase($params);
        $tmpTableName = "_tmp_mymr_" . date("YmdHis") . "_" . uniqid();
        $database->createTmpTable($tmpTableName);
        return array(
            $database->getTable($params['table']),
            $database->getTable($tmpTableName)
        );
    }

    /**
     * Constructs Database object.
     *
     * @param  array $params
     * @return \MyMR\Database
     */
    protected function _createDatabase($params)
    {
    
        $dsn = "mysql:dbname={$params['database']};" .
            "host={$params['host']};" .
            "port={$params['port']}";
        return new Database($dsn, $params['user'], $params['pass']);
    }
}
