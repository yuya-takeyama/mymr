<?php
/**
 * Base class of Map/Reduce procedure definition.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Table;

abstract class Base
{
    protected $_inputTable;
    protected $_outputTable;
    protected $_tmpTable;

    public function execute($input, $output, $tmp)
    {
        $this->_inputTable  = $input;
        $this->_outputTable = $output;
        $this->_tmpTable    = $tmp;

        $this->_tmpTable->truncate();
        foreach ($this->_inputTable->fetchAll() as $record) {
            $this->map($record);
        }

        $this->_outputTable->truncate();
        foreach ($this->_tmpTable->fetchAllGroup() as $record) {
            $values = array_map(function ($json) {
                return json_decode($json, true);
            }, explode("\n", $record['values']));
            $result = $this->reduce($record['key'], $values);
            $this->_outputTable->insert(array_merge(
                array('key' => $record['key']),
                $result
            ));
        }
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
