<?php
namespace MyMR;

use \MyMR\Progress,
    \MyMR\Emitter;

use \Symfony\Component\Console\Output\OutputInterface;

class MapReduce
{
    const PROGRESS_DATE_FORMAT = '[Y-m-d H:i:s]';

    /**
     * @var \MyMR\Table
     */
    private $inputTable;

    /**
     * @var \MyMR\Table
     */
    private $intermediateTable;

    /**
     * @var \MyMR\Table
     */
    private $outputTable;

    /**
     * @var callable
     */
    private $mapper;

    /**
     * @var callable
     */
    private $reducer;

    public function __construct($inputTable, $intermediateTable, $outputTable, $mapper, $reducer)
    {
        $this->inputTable        = $inputTable;
        $this->intermediateTable = $intermediateTable;
        $this->outputTable       = $outputTable;
        $this->mapper            = $mapper;
        $this->reducer           = $reducer;
    }

    public function execute($output)
    {
        $output->writeln('Beggining Map phase.');
        $this->executeMapper($output);
        $output->writeln('Beggining Reduce phase.');
        $this->executeReducer($output);
        $output->writeln('Completed.');
    }

    public function executeMapper(OutputInterface $output)
    {
        $this->intermediateTable->create();

        $records  = $this->inputTable->fetchAll();
        $progress = new Progress(count($records), $output, self::PROGRESS_DATE_FORMAT);
        $emitter  = new Emitter($this->intermediateTable);

        foreach ($records as $i => $record) {
            call_user_func_array($this->mapper, array($record, $emitter));
            $progress->setCurrentPosition($i + 1);
        }
    }

    public function executeReducer(OutputInterface $output)
    {
        $this->outputTable->truncate();

        $records  = $this->intermediateTable->fetchAllGroup();
        $progress = new Progress(count($records), $output, self::PROGRESS_DATE_FORMAT);

        foreach ($records as $i => $record) {
            $values = array_map(function ($json) {
                return \json_decode($json, true);
            }, explode("\n", $record['values']));
            $result = call_user_func_array($this->reducer, array($record['key'], $values));
            $this->outputTable->insert(array_merge(
                array('key' => $record['key']),
                $result
            ));
            $progress->setCurrentPosition($i + 1);
        }
    }
}
