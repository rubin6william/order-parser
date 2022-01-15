<?php

namespace App\Helpers;

/**
 * Class CustomerOrderRecordParser
 * @package App\Services
 */
class CustomerOrderRecordParser
{
    const DISCOUNT_TYPE_PERCENTAGE = 'PERCENTAGE';

    const DISCOUNT_TYPE_DOLLAR = 'DOLLAR';

    protected ?int $orderId;

    protected ?string $orderDate;

    protected ?float $totalOrderValue;

    protected ?float $averageUnitPrice;

    protected ?int $distinctUnitCount;

    protected ?int $totalUnitsCount;

    protected ?string $customerState;

    /**
     * @param array $record
     */
    public function __construct(public array $record)
    {
    }

    /**
     * @param string $name
     * @return void
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * @return void
     */
    public function parse()
    {
        $this->orderId = @$this->record['order_id'];
        $this->orderDate = @$this->record['order_date'];
        $this->customerState = @$this->record['customer']['shipping_address']['state'];

        $this->totalOrderValue = 0;
        $this->totalUnitsCount = 0;
        $this->averageUnitPrice = 0;

        $items = (array) @$this->record['items'];

        $this->distinctUnitCount = count($items);

        foreach($items as $key => $item) {
            $totalItemValue = ($item['unit_price'] * $item['quantity']);
            $this->averageUnitPrice += $totalItemValue;
            $discount = @$this->record['discounts'][$key];

            if (@$discount['type'] == static::DISCOUNT_TYPE_DOLLAR) {
                $totalItemValue -= $discount['value'];
            } else if (@$discount['type'] == static::DISCOUNT_TYPE_PERCENTAGE) {
                $totalItemValue -= round((($totalItemValue * $discount['value']) / 100), 2);
            }

            $this->totalOrderValue += $totalItemValue;
            $this->totalUnitsCount += $item['quantity'];
        }

        $this->averageUnitPrice = ($this->averageUnitPrice && $this->totalUnitsCount) ?
            round(($this->averageUnitPrice / $this->totalUnitsCount), 2) :
            0;

        $this->totalOrderValue -= @$this->record['shipping_price'];
    }
}