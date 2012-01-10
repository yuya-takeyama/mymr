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
    protected $_tableName;
    protected $_pdo;

    public function __construct($tableName, PDO $pdo)
    {
        $this->_tableName = $tableName;
        $this->_pdo = $pdo;
    }

    public function fetchAll()
    {
        $stmt = $this->_pdo->query("SELECT * FROM `{$this->_tableName}`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public  function fetchAllGroup()
    {
        $sql = "SELECT `key`, GROUP_CONCAT(`value` SEPARATOR '\\n') AS `values` " .
            "FROM `{$this->_tableName}` " .
            "GROUP BY `key`";
        $stmt = $this->_pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function truncate()
    {
        return $this->_pdo->exec("TRUNCATE TABLE `{$this->_tableName}`");
    }

    public function insert($record)
    {
        $sql = "INSERT INTO `{$this->_tableName}` " .
            "(`key`, `value`) VALUES (?, ?)";
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute(array($record['key'], $record['value']));
    }
}
