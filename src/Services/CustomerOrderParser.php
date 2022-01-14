<?php

namespace App\Services;

/**
 * Class CustomerOrderParser
 * @package App\Services
 */
class CustomerOrderParser
{
    const CHUNK_SIZE = 50;

    /**
     * @param DownloadFileFromUrl $downloadFileFromUrl
     */
    public function __construct(public DownloadFileFromUrl $downloadFileFromUrl)
    {
    }

    /**
     * @param string $url
     * @return void
     * @throws \Exception
     */
    public function parse(string $url)
    {
        $orderFile = $this->downloadFileFromUrl->download($url);

        $fileHandle = fopen($orderFile, 'r');

        $lineNumber = 1;

        $chunk = [];

        while(($rawString = fgets($fileHandle)) !== false) {
            $decodedString = json_decode($rawString, true);
            $recordParser = new CustomerOrderRecordParser($decodedString);
            $recordParser->parse();

            $chunk[] = [
              'order_id' => $recordParser->orderId,
              'order_datetime' => $recordParser->orderDate,
              'total_order_value' => $recordParser->totalOrderValue,
              'average_unit_price' =>  $recordParser->averageUnitPrice,
              'distinct_unit_count' => $recordParser->distinctUnitCount,
              'total_units_count' => $recordParser->totalUnitsCount,
              'customer_state' => $recordParser->customerState,
            ];

            $lineNumber++;

            if ($lineNumber == static::CHUNK_SIZE) {
                // output to file and reset chunk
                $lineNumber = 1;
                $chunk = [];
            }
        }

        if (count($chunk)) {
            //output remaining items
        }
    }
}