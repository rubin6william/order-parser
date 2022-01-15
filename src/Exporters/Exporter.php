<?php

namespace App\Exporters;

use Exception;

/**
 * Class Exporter
 * @package App\Services
 */
class Exporter
{
    /**
     * List of available exporters
     *
     * @var array|string[]
     */
    protected array $exporters = [
        'csv' => CsvExporter::class,
        'jsonl' => JsonLinesExporter::class,
        'yaml' => YamlExporter::class
    ];

    /**
     * @param string $format
     * @param string $outputFile
     * @param array $data
     * @return ExporterInterface
     * @throws Exception
     */
    public function get(string $format, string $outputFile, array $data = []): ExporterInterface
    {
        if (!array_key_exists($format, $this->exporters)) {
            throw new Exception('Invalid format');
        }

        return new $this->exporters[$format]($outputFile, $data);
    }
}