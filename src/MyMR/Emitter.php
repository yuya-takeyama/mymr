<?php
/**
 * Emitter for intermediate table.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Table;

class Emitter
{
    private $intermediateTable;

    public function __construct(Table $intermediateTable)
    {
        $this->intermediateTable = $intermediateTable;
    }

    public function emit($key, $value)
    {
        $this->intermediateTable->insert(array(
            'key'   => $key,
            'value' => \json_encode($value),
        ));
    }
}
