<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/2/16
 * Time: 3:21 PM
 */

namespace App\Billing\Contracts;


interface TellerInterface
{
    public function charge(array $data);
}