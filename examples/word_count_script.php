<?php
/**
 * Builder interface proposal.
 *
 * @author Yuya Takeyama
 */
require_once __DIR__ . '/../MyMr/Builder.php';

use \MyMR\Builder,
    \MyMR\Table;

$builder = new Builder;

$builder->setInputTable('root@localhost/mymr_wordcount_example/texts');
$builder->setOutputTable('root@localhost/mymr_wordcount_example/word_counts');

$builder->setMapper(function ($record, $emitter) {
    $words = preg_split('/\s+/u', $record['text']);
    foreach ($words as $record) {
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

$builder->getMapReduce->run();
