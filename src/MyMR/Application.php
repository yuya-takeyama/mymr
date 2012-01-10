<?php
/**
 * MyMR Application.
 *
 * @author Yuya Takeyama
 */
namespace MyMR;

use \MyMR\Command\ExecuteCommand;

use \Symfony\Component\Console\Application as ConsoleApplication,
    \Symfony\Component\Console\Input\InputInterface;

class Application extends ConsoleApplication
{
    /**
     * Constructor.
     *
     * @param  string $version
     */
    public function __construct($version = NULL)
    {
        parent::__construct('MyMR', $version);
        $this->add(new ExecuteCommand);
    }
}
