<?php

namespace App\Services;

/**
 * Converts given array to csv and outputs in given file
 *
 * Class CsvExporter
 * @package App\Services
 */
class CsvExporter implements ExporterInterface
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
        $headers = isset($this->data[0]) ?
            array_keys($this->data[0]) :
            [];

        $fp = fopen($this->outputFile . '.csv', 'w');

        fputcsv($fp, $headers);

        foreach($this->data as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);
    }
}