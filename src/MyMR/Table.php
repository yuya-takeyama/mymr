<?php
/**
 * Table Data Gateway.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \PDO;

class Table
{
    protected $_database;
    protected $_name;

    public function __construct($database, $name)
    {
        $this->_database = $database;
        $this->_name = $name;
    }

    public function fetchAll()
    {
        $stmt = $this->_database->query("SELECT * FROM `{$this->_name}`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public  function fetchAllGroup()
    {
        $sql = "SELECT `key`, GROUP_CONCAT(`value` SEPARATOR '\\n') AS `values` " .
            "FROM `{$this->_name}` " .
            "GROUP BY `key`";
        $stmt = $this->_database->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function truncate()
    {
        return $this->_database->exec("TRUNCATE TABLE `{$this->_name}`");
    }

    public function insert($record)
    {
        $cols = $values = array();
        $valueCount = 0;
        foreach ($record as $key => $value) {
            $cols[] = $key;
            $values[] = $value;
            $valueCount++;
        }
        $colsSpec = join(', ', array_map(function ($col) {
            return "`{$col}`";
        }, $cols));
        $placeHolder = join(', ', array_fill(0, $valueCount, '?'));
        $sql = "INSERT INTO `{$this->_name}` " .
            "({$colsSpec}) VALUES ({$placeHolder})";
        $stmt = $this->_database->prepare($sql);
        $stmt->execute($values);
    }
}
