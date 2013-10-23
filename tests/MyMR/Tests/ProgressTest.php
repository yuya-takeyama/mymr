<?php
namespace MyMR\Tests;

use \MyMR\Progress;

use \SymfonyX\Component\Console\Output\SpyOutput;

class ProgressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideInputsOfProgress
     */
    public function progress_should_work_correctly($max, $inputs, $expected)
    {
        $spy = new SpyOutput;
        $progress = new Progress($max, $spy);
        foreach ($inputs as $input) {
            $progress->setCurrentPosition($input);
        }
        $this->assertEquals($expected, $spy->getMessage());
    }

    public function provideInputsOfProgress()
    {
        $data = array();

        $data[] = array(
            1,
            array(1),
            "0/1   (0%)" . PHP_EOL .
            "1/1 (100%)". PHP_EOL
        );

        $data[] = array(
            10,
            range(1, 10),
            " 0/10   (0%)" . PHP_EOL .
            " 1/10  (10%)" . PHP_EOL .
            " 2/10  (20%)" . PHP_EOL .
            " 3/10  (30%)" . PHP_EOL .
            " 4/10  (40%)" . PHP_EOL .
            " 5/10  (50%)" . PHP_EOL .
            " 6/10  (60%)" . PHP_EOL .
            " 7/10  (70%)" . PHP_EOL .
            " 8/10  (80%)" . PHP_EOL .
            " 9/10  (90%)" . PHP_EOL .
            "10/10 (100%)" . PHP_EOL
        );

        $data[] = array(
            3,
            range(1, 3),
            "0/3   (0%)" . PHP_EOL .
            "1/3  (33%)" . PHP_EOL .
            "2/3  (67%)" . PHP_EOL .
            "3/3 (100%)" . PHP_EOL
        );

        $data[] = array(
            100,
            range(1, 100),
            "  0/100   (0%)" . PHP_EOL .
            " 10/100  (10%)" . PHP_EOL .
            " 20/100  (20%)" . PHP_EOL .
            " 30/100  (30%)" . PHP_EOL .
            " 40/100  (40%)" . PHP_EOL .
            " 50/100  (50%)" . PHP_EOL .
            " 60/100  (60%)" . PHP_EOL .
            " 70/100  (70%)" . PHP_EOL .
            " 80/100  (80%)" . PHP_EOL .
            " 90/100  (90%)" . PHP_EOL .
            "100/100 (100%)" . PHP_EOL
        );

        return $data;
    }
}
