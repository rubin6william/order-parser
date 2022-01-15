<?php

namespace App\Services;

/**
 * Class ExportAsCsv
 * @package App\Services
 */
class ExportAsCsv
{

    /**
     * @param string $outputFile
     * @param array $headers
     * @param array $data
     */
    public function __construct(public string $outputFile, public array $headers = [], public array $data = [])
    {
    }

    public function output()
    {
        $fp = fopen($this->outputFile . '.csv', 'w');

        fputcsv($fp, $this->headers);

        foreach($this->data as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);
    }
}