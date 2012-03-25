<?php
/**
 * Class based word-count example for MyMR.
 *
 * @author Yuya Takeyama
 */
use \MyMR\Base,
    \MyMR\Emitter;

class WordCount extends Base
{
    public function map($record, Emitter $emitter)
    {
        $words = preg_split('/\s+/u', $record['text']);
        foreach ($words as $word) {
            $emitter->emit($word, 1);
        }
    }

    public function reduce($key, $values)
    {
        $sum = 0;
        foreach ($values as $count) {
            $sum += $count;
        }
        return array('count' => $sum);
    }
}
