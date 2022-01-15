<?php

namespace App\Command;

use App\Services\CustomerOrderParser;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ParseOrderCommand
 * @package App\Command
 */
class ParseOrderCommand extends Command
{
    const ALLOWED_OUTPUT_FILE_FORMATS = [
      'csv',
      'jsonl',
      'yaml'
    ];

    protected static $defaultName = 'app:parse-order';

    protected static $defaultDescription = 'Parses the customer order file from given url and generates summarized output file in the requested format';

    /**
     * @param CustomerOrderParser $parser
     * @param string|null $name
     */
    public function __construct(public CustomerOrderParser $parser, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->addOption(name: 'output-file-format', mode: InputArgument::OPTIONAL,
                description: 'Output file format. Allowed formats are ' . implode(',', static::ALLOWED_OUTPUT_FILE_FORMATS) . '.', default: 'csv')
            ->addOption(name: 'input-file-url', mode: InputArgument::OPTIONAL,
                description: 'Url of the input file', default: 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = $input->getOptions();

        $inputFileUrl = $options['input-file-url'] ?? '';
        $outputFileFormat = $options['output-file-format'] ?? '';

        if (!strlen($inputFileUrl)) {
            $output->writeln('Input url is required');
            return Command::INVALID;
        }

        if (!in_array($outputFileFormat, static::ALLOWED_OUTPUT_FILE_FORMATS)) {
            $output->writeln('Provided output file format is invalid');
            return Command::INVALID;
        }

        $this->parser->parse($inputFileUrl);

        $output->writeln('Output files are generated in current directory');

        return Command::SUCCESS;
    }
}