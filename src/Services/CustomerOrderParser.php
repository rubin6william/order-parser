<?php

namespace App\Services;

use App\Helpers\CustomerOrderRecordParser;

/**
 * Class CustomerOrderParser
 * @package App\Services
 */
class CustomerOrderParser
{
    const CHUNK_SIZE = 10000;

    /**
     * @param DownloadFileFromUrl $downloadFileFromUrl
     */
    public function __construct(public DownloadFileFromUrl $downloadFileFromUrl)
    {
    }

    /**
     * Parses the input file and outputs file with summarized result
     *
     * @param string $url
     * @return void
     * @throws \Exception
     */
    public function parse(string $url)
    {
        // Remove any existing output files
        foreach(glob('out*.*') as $filename) {
            unlink($filename);
        }

        $orderFile = $this->downloadFileFromUrl->download($url);

        $fileHandle = fopen($orderFile, 'r');

        $lineNumber = 1;

        $chunk = [];

        $headers = [];

        $files = 1;

        while(($rawString = fgets($fileHandle)) !== false) {
            $decodedString = json_decode($rawString, true);
            $recordParser = new CustomerOrderRecordParser($decodedString);

            $recordParser->parse();

            if ($recordParser->totalOrderValue > 0) {
                $chunk[] = [
                    'order_id' => $recordParser->orderId,
                    'order_datetime' => $recordParser->orderDate,
                    'total_order_value' => $recordParser->totalOrderValue,
                    'average_unit_price' =>  $recordParser->averageUnitPrice,
                    'distinct_unit_count' => $recordParser->distinctUnitCount,
                    'total_units_count' => $recordParser->totalUnitsCount,
                    'customer_state' => $recordParser->customerState,
                ];

                if (!count($headers)) {
                    $headers = array_keys($chunk[0]);
                }
            }

            $lineNumber++;

            if ($lineNumber > static::CHUNK_SIZE) {
                // output to file and reset chunk
                (new ExportAsCsv("out{$files}", $headers, $chunk))->output();
                $files++;
                $lineNumber = 1;
                $chunk = [];
            }
        }

        if (count($chunk)) {
            (new ExportAsCsv("out{$files}", $headers, $chunk))->output();
        }
    }
}