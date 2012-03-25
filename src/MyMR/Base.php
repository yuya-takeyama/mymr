<?php
/**
 * Base class of Map/Reduce procedure definition.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Table,
    \MyMR\Progress;

abstract class Base
{
    const PROGRESS_DATE_FORMAT = '[Y-m-d H:i:s]';

    protected $_inputTable;
    protected $_outputTable;
    protected $_tmpTable;

    public function execute($inputTable, $outputTable, $tmp, $output)
    {
        $this->_inputTable  = $inputTable;
        $this->_outputTable = $outputTable;
        $this->_tmpTable    = $tmp;

        $output->writeln('Beggining Map phase.');
        $this->_tmpTable->truncate();
        $records = $this->_inputTable->fetchAll();
        $progress = new Progress(count($records), $output, self::PROGRESS_DATE_FORMAT);
        foreach ($this->_inputTable->fetchAll() as $i => $record) {
            $this->map($record);
            $progress->setCurrentPosition($i + 1);
        }

        $output->writeln('Beggining Reduce phase.');
        $this->_outputTable->truncate();
        $records = $this->_tmpTable->fetchAllGroup();
        $progress = new Progress(count($records), $output, self::PROGRESS_DATE_FORMAT);
        foreach ($records as $i => $record) {
            $values = array_map(function ($json) {
                return json_decode($json, true);
            }, explode("\n", $record['values']));
            $result = $this->reduce($record['key'], $values);
            $this->_outputTable->insert(array_merge(
                array('key' => $record['key']),
                $result
            ));
            $progress->setCurrentPosition($i + 1);
        }
        $output->writeln('Completed.');
    }

    public function emit($key, $value)
    {
        $this->_tmpTable->insert(array(
            'key'   => $key,
            'value' => \json_encode($value),
        ));
    }

    abstract public function map($value);

    abstract public function reduce($key, $value);
}
