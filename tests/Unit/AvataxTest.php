<?php

namespace Smbear\Avatax\Tests\Unit;

use Smbear\Avatax\Tests\TestCase;
use Smbear\Avatax\Facades\Avatax;

class AvataxTest extends TestCase
{
    public function test_set_local()
    {
        $_this = Avatax::setLocal('en');

        $this->assertEquals('en', $_this->local);
    }

    public function test_create_transaction()
    {
        $type = 'SalesOrder';

        $res = Avatax::setAddress($this->getAddress())
            ->setOrder([
                'documentCode'     => 'FS000000001',
                'customerCode'     => 123456789,
                'entityUseCode'    => 123456789,
                'currencyCode'     => 'USD',
                'exchangeRate'     => 1,
                'description'      => '描述',
                'purchaseOrderNo'  => 'FS000000001',
                'salespersonCode'  => 123,
                'lines'            =>[
                    [
                        'amount'=> 10000,
                        'quantity'=>3,
                        'itemCode'=>'FS000001',
                        'taxCode' =>'Shipping',
                        'lineNumber' =>1,
                        'description' => '描述'
                    ],
                    [
                        'amount'=> 20000,
                        'quantity'=>3,
                        'itemCode'=>'FS000002',
                        'taxCode' =>'Shipping',
                        'lineNumber' =>2,
                        'description' => '描述'
                    ],
                ],
            ])
            ->createTransaction('SalesOrder');

        $this->assertIsArray($res);
    }
}