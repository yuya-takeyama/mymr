<?php
/**
 * Base class of Map/Reduce procedure definition.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Table,
    \MyMR\Progress,
    \MyMR\Emitter;

abstract class Base
{
    public function execute($inputTable, $outputTable, $intermediateTable, $output)
    {
        $mapreduce = new MapReduce(
            $inputTable,
            $intermediateTable,
            $outputTable,
            array($this, 'map'),
            array($this, 'reduce')
        );
        $mapreduce->execute($output);
    }

    abstract public function map($value, Emitter $emitter);

    abstract public function reduce($key, $value);
}
