<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 8/6/16
 * Time: 3:48 PM
 */

namespace App\Stock;


class Price
{
    private $amount;

    public function __construct($amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Price must be a positive value');
        }
        $this->amount = intval($amount);
    }

    public static function fromInputString($amount)
    {
        if (!is_numeric($amount)) {
            throw new \InvalidArgumentException('Price must be a numeric value');
        }

        return new static(intval(floatval($amount) * 100));
    }

    public static function fromCents($amount)
    {
        if (!is_int(intval($amount))) {
            throw new \InvalidArgumentException('Price from cents must be an integer value');
        }

        return new static($amount);
    }

    public function inCents()
    {
        return $this->amount;
    }

    public function asCurrencyString()
    {
        return money_format('%i', $this->amount / 100);
    }

    public function asRandCentsFloat()
    {
        return ($this->amount / 100);
    }

    public function __toString()
    {
        return $this->asCurrencyString();
    }
}