MyMR
====

master: [![Build Status](https://secure.travis-ci.org/yuya-takeyama/mymr.png?branch=master)](http://travis-ci.org/yuya-takeyama/mymr)
develop: [![Build Status](https://secure.travis-ci.org/yuya-takeyama/mymr.png?branch=develop)](http://travis-ci.org/yuya-takeyama/mymr)

MapReduce framework using PHP and MySQL.

Currently this application is under heavy development. Some interfaces may change.

Features
--------

### Class based MapReduce definition.

```php
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
```

### Builder script based MapReduce definition.

```php
<?php
/**
 * Builder script based word-count example for MyMR.
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
```

Running examples
----------------

Some examples are in `./examples` directory.

### Test data installation

```
$ mysql -u USER --database INPUT_DATABASE -p < examples/sql/wordcount_example_db.sql
```

### Running class based example

```
$ ./bin/mymr class examples/WordCount.php -i USER:PASSWORD@DATABASE/INPUT_DATABASE/texts -o USER:PASSWORD@DATABASE/OUTPUT_DATABASE/word_counts
```

### Running builder script based example

`-i` and `-o` parameter is optional if they are defined in builder script.

```
$ ./bin/mymr builder examples/word_count_script.php
```

Articles
--------

- [MapReduce casually using PHP and MySQL](http://blog.yuyat.jp/archives/1706) (ja)

Author
-----

Yuya Takeyama [http://yuyat.jp/](http://yuyat.jp/)
