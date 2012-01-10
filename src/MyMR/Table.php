<?php
/**
 * Table Data Gateway.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

class Table
{
    protected $_tableName;
    protected $_pdo;

    public function __construct($tableName, $pdo)
    {
        $this->_tableName = $tableName;
        $this->_pdo = $pdo;
    }
}
