<?php
/**
 * WordCount example for MyMR.
 *
 * @author Yuya Takeyama
 */
use \MyMR\Base;

class WordCount extends Base
{
    public function map($record)
    {
        $words = preg_split('/\s+/u', $record['text']);
        foreach ($words as $word) {
            $this->emit($word, array('count' => 1));
        }
    }

    public function reduce($key, $values)
    {
        $result = array('count' => 0);
        foreach ($values as $value) {
            $result['count'] += $value['count'];
        }
        return $result;
    }
}
