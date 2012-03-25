<?php
namespace MyMR;

use \MyMR\MapReduce;

class Builder
{
    public function setInputTable()
    {}

    public function setOutputTable()
    {}

    public function setMapper()
    {}

    public function setReducer()
    {}

    public function getMapReduce()
    {
        return new MapReduce;
    }
}
