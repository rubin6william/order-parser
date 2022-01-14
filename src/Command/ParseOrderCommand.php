<?php

namespace App\Command;

use App\Services\CustomerOrderParser;
use App\Services\ParseOrderFileFormUrl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ParseOrderCommand
 * @package App\Command
 */
class ParseOrderCommand extends Command
{
    protected static $defaultName = 'app:parse-order';

    protected static $defaultDescription = 'Parses the customer order file from given url and generates summarized output file in the requested format';

    public function __construct(public CustomerOrderParser $parser, string $name = null)
    {
        parent::__construct($name);
    }

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
        $this->parser->parse('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl');

        return Command::SUCCESS;
    }
}