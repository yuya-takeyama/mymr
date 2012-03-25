<?php
/**
 * Database Gateway.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Table,
    \MyMR\IntermediateTable;

use \PDO;

class Database
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Constructor.
     *
     * @param PDO $pdo
     */
    public function __construct($dsn, $user, $password)
    {
        $this->dsn      = $dsn;
        $this->user     = $user;
        $this->password = $password;
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
     * Gets IntermediateTable object.
     *
     * @return Table
     */
    public function getIntermediateTable($tableName)
    {
        return new IntermediateTable($this, $tableName);
    }

    public function createTemporaryTable($tableName)
    {
        $this->_connect();
        $sql = "CREATE TEMPORARY TABLE `{$tableName}` ( " .
               "`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
               "`key` VARCHAR(64) NOT NULL, " .
               "`value` text NOT NULL " .
               ") ENGINE=InnoDB";
        $this->pdo->exec($sql);
    }

    public function query($sql)
    {
        $this->_connect();
        return $this->pdo->query($sql);
    }

    public function prepare($sql)
    {
        $this->_connect();
        return $this->pdo->prepare($sql);
    }

    public function exec($sql)
    {
        $this->_connect();
        return $this->pdo->exec($sql);
    }

    /**
     * Lazy connection.
     */
    private function _connect()
    {
        if (empty($this->pdo)) {
            $this->pdo = new PDO($this->dsn, $this->user, $this->password);
        }
    }
}
