<?php
/**
 * @author Yuya Takeyama
 */
namespace MyMR;

use \Symfony\Component\Console\Output\OutputInterface;

class Progress
{
    /**
     * @var int
     */
    protected $max;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Constructor.
     *
     * @param  int             $max    Max count of progression.
     * @param  OutputInterface $output
     */
    public function __construct($max, OutputInterface $output)
    {
        $this->max = $max;
        $this->output = $output;
    }

    public function setCurrentPosition($position)
    {
        $max = $this->max;
        if ($position === 1) {
            $this->output->writeln(sprintf('0/%d   (0%%)', $max));
        }
        if ($position === $max) {
            $this->output->writeln(sprintf('%d/%d (%d%%)', 1, $max, 100));
        }
    }
}
