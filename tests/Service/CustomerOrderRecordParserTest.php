<?php

namespace App\Tests\Service;

use App\Helpers\CustomerOrderRecordParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CustomerOrderRecordParserTest
 * @package App\Tests\Service
 */
class CustomerOrderRecordParserTest extends KernelTestCase
{
    public function testExtractionOfOrderId()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals(1001, $parser->orderId);
    }

    public function testExtractionOfOrderDate()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals('Fri, 08 Mar 2019 12:13:29 +0000', $parser->orderDate);
    }

    public function testExtractionOfCustomerState()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals('VICTORIA', $parser->customerState);
    }

    public function testTotalValueCalculation()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals(352.79, $parser->totalOrderValue);
    }

    public function testDistinctUnitCountCalculation()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals(2, $parser->distinctUnitCount);
    }

    public function testTotalUnitsCountCalculation()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals(6, $parser->totalUnitsCount);
    }

    public function testAverageUnitPriceCalculation()
    {
        $record = $this->readTestFile();
        $parser = new CustomerOrderRecordParser($record);
        $parser->parse();

        $this->assertEquals(59.96, $parser->averageUnitPrice);
    }

    protected function readTestFile() {
        return json_decode(file_get_contents('tests/files/input.jsonl'), true);
    }
}