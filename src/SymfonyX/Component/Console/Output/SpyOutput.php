<?php
namespace SymfonyX\Component\Console\Output;

use \Symfony\Component\Console\Output\OutputInterface,
    \Symfony\Component\Console\Output\Output;

class SpyOutput extends Output
{
    private $messages = array();

    public function doWrite($message, $newline)
    {
        $this->messages[] = $message . ($newline ? PHP_EOL : '');
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getMessage()
    {
        return join('', $this->messages);
    }
}
