<?php
/**
 * Builder interface based word-count example for MyMR.
 *
 * @author Yuya Takeyama
 */
use \MyMR\Builder;

$builder = new Builder;

$builder->setInputTable('root@localhost/mymr_wordcount_example/texts');
$builder->setOutputTable('root@localhost/mymr_wordcount_example/word_counts');

$builder->setMapper(function ($record, $emitter) {
    $words = preg_split('/\s+/u', $record['text']);
    foreach ($words as $word) {
        $emitter->emit($word, 1);
    }
});

$builder->setReducer(function ($key, $values) {
    $sum = 0;
    foreach ($values as $count) {
        $sum += $count;
    }
    return array('count' => $sum);
});

return $builder;
