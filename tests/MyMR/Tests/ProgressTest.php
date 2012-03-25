<?php
namespace MyMR\Tests;

use \MyMR\Progress;

use \SymfonyX\Component\Console\Output\SpyOutput;

class ProgressTest extends \PHPUnit_Framework_Testcase
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
            <<< __EXPECTED__
0/1 (0%)
1/1 (100%)

__EXPECTED__
        );

        return $data;
    }
}
