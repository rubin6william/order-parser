<?php

namespace App\Exporters;

/**
 * Converts given array to Yaml and outputs to given file
 *
 * Class YamlExporter
 * @package App\Services
 */
class YamlExporter implements ExporterInterface
{

    /**
     * @param string $outputFile
     * @param array $data
     */
    public function __construct(public string $outputFile, public array $data = [])
    {
    }

    public function output()
    {
        yaml_emit_file($this->outputFile . '.yaml', $this->data);
    }
}