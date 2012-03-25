<?php
namespace MyMR;

use \MyMR\Table;

class IntermediateTable extends Table
{
    /**
     * Creates self.
     *
     * @param  string $tableName
     * @return
     */
    public function create()
    {
        $this->database->createTemporaryTable($this->name);
    }
}
