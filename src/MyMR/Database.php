<?php
/**
 * Database Gateway.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Table;

use \PDO;

class Database
{
    /**
     * @var PDO
     */
    protected $_pdo;

    /**
     * Constructor.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->_pdo = $pdo;
    }

    /**
     * Gets Table object.
     *
     * @return Table
     */
    public function getTable($tableName)
    {
        return new Table($this, $tableName);
    }

    /**
     * Creates table for mapped records.
     *
     * @param  string $tableName
     * @return
     */
    public function createTmpTable($tableName)
    {
        $sql = "CREATE TEMPORARY TABLE `{$tableName}` ( " .
            "`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
            "`key` VARCHAR(64) NOT NULL, " .
            "`value` text NOT NULL " .
            ") ENGINE=InnoDB";
        $this->exec($sql);
    }

    public function query($sql)
    {
        return $this->_pdo->query($sql);
    }

    public function prepare($sql)
    {
        return $this->_pdo->prepare($sql);
    }

    public function exec($sql)
    {
        return $this->_pdo->exec($sql);
    }
}
