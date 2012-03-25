<?php
namespace MyMR;

use \MyMR\MapReduce;

use PDO;

class Builder
{
    private $inputTable;

    private $intermediateTable;

    private $outputTable;

    private $mapper;

    private $reducer;

    public function setInputTable($uri)
    {
        $params = Util::parseDatabaseUri($uri);
        $database = $this->_createDatabase($params);
        $this->inputTable = $database->getTable($params['table']);
    }

    public function setOutputTable($uri)
    {
        $params = Util::parseDatabaseUri($uri);
        $database = $this->_createDatabase($params);
        $tmpTableName = "_tmp_mymr_" . date("YmdHis") . "_" . uniqid();
        $database->createTmpTable($tmpTableName);
        $this->outputTable = $database->getTable($params['table']);
        $this->intermediateTable = $database->getTable($tmpTableName);
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    public function setReducer($reducer)
    {
        $this->reducer = $reducer;
    }

    public function getMapReduce()
    {
        return new MapReduce(
            $this->inputTable,
            $this->intermediateTable,
            $this->outputTable,
            $this->mapper,
            $this->reducer
        );
    }

    /**
     * Constructs Database object.
     *
     * @param  array $params
     * @return PDO
     */
    protected function _createDatabase($params)
    {
        $dsn = "mysql:dbname={$params['database']};" .
               "host={$params['host']};" .
               "port={$params['port']}";
        return new Database(new PDO($dsn, $params['user'], $params['pass']));
    }
}
