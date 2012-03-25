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
    protected $database;
    protected $name;

    public function __construct($database, $name)
    {
        $this->database = $database;
        $this->name = $name;
    }

    public function fetchAll()
    {
        $stmt = $this->database->query("SELECT * FROM `{$this->name}`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public  function fetchAllGroup()
    {
        $sql = "SELECT `key`, GROUP_CONCAT(`value` SEPARATOR '\\n') AS `values` " .
            "FROM `{$this->name}` " .
            "GROUP BY `key`";
        $stmt = $this->database->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function truncate()
    {
        return $this->database->exec("TRUNCATE TABLE `{$this->name}`");
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
        $sql = "INSERT INTO `{$this->name}` " .
            "({$colsSpec}) VALUES ({$placeHolder})";
        $stmt = $this->database->prepare($sql);
        $stmt->execute($values);
    }
}
