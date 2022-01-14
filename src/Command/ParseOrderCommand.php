<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseOrderCommand extends Command
{
    protected static $defaultName = 'app:parse-order';

    protected static $defaultDescription = 'Downloads and parses customer order file and generates clean output file in the requested format';

    protected function configure()
    {

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo 'hello';

        return Command::SUCCESS;
    }
}