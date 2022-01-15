<?php

namespace App\Exporters;

use Rs\JsonLines\Exception\InvalidJson;
use Rs\JsonLines\Exception\NonTraversable;
use Rs\JsonLines\JsonLines;

/**
 * Converts given array to json lines and outputs in given file
 *
 * Class JsonLinesExporter
 * @package App\Services
 */
class JsonLinesExporter implements ExporterInterface
{

    /**
     * @param string $outputFile
     * @param array $data
     */
    public function __construct(public string $outputFile, public array $data = [])
    {
    }

    /**
     * @return void
     * @throws InvalidJson
     * @throws NonTraversable
     */
    public function output()
    {
        (new JsonLines())->enlineToFile($this->data, $this->outputFile . '.jsonl');
    }
}