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
     * @var int
     */
    protected $indicatorLength;

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
        $this->indicatorLength = strlen((string)$max) * 2 + 1;
    }

    public function setCurrentPosition($position)
    {
        $max = $this->max;
        if ($position === 1) {
            $indicator = str_pad("0/{$max}", $this->indicatorLength, ' ', STR_PAD_LEFT);
            $this->output->writeln(sprintf('%s   (0%%)', $indicator, $max));
        }
        if ($position === $max) {
            $indicator = str_pad("{$max}/{$max}", $this->indicatorLength, ' ', STR_PAD_LEFT);
            $this->output->writeln(sprintf('%s (100%%)', $indicator, $max));
        }
    }
}
