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
     * @var
     */
    protected $flags;

    /**
     * Constructor.
     *
     * @param  int             $max    Max count of progression.
     * @param  OutputInterface $output
     */
    public function __construct($max, OutputInterface $output, $dateFormat = NULL)
    {
        $this->max = $max;
        $this->output = $output;
        $this->indicatorLength = strlen((string)$max) * 2 + 1;
        $this->flags = array(true);
        $this->dateFormat = $dateFormat;
    }

    public function setCurrentPosition($position)
    {
        $max = $this->max;
        if ($position === 1) {
            $indicator = str_pad("0/{$max}", $this->indicatorLength, ' ', STR_PAD_LEFT);
            $this->writeDate();
            $this->output->writeln(sprintf('%s   (0%%)', $indicator, $max));
        }
        if ($position === $max) {
            $indicator = str_pad("{$max}/{$max}", $this->indicatorLength, ' ', STR_PAD_LEFT);
            $this->writeDate();
            $this->output->writeln(sprintf('%s (100%%)', $indicator, $max));
        } else {
            $key = (int)floor($position / $max * 10);
            if (array_key_exists($key, $this->flags) === false) {
                $per = round($position / $max * 100);
                $indicator = str_pad("{$position}/{$max}", $this->indicatorLength, ' ', STR_PAD_LEFT);
                $this->writeDate();
                $this->output->writeln("{$indicator}  ({$per}%)");
                $this->flags[$key] = true;
            }
        }
    }

    public function writeDate()
    {
        if ($this->dateFormat) {
            $this->output->write(date($this->dateFormat) . ' ');
        }
    }
}
