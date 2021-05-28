<?php

namespace Smbear\Avatax\Tests\Unit;

use Smbear\Avatax\Tests\TestCase;
use Smbear\Avatax\Services\AvataxAddressService;

class AddressTest extends TestCase
{
    /**
     * @Notes:
     *
     * @throws \Smbear\Avatax\Exceptions\AvataxException
     * @throws \Smbear\Avatax\Exceptions\ConfigException
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 16:31
     * @test
     */
    public function t1est_address()
    {
        $addressService = new AvataxAddressService();

        $address = [
            'city'       => 'Irvine',
            'country'    => 'US',
            'postalCode' => '92615',
            'region'     => 'CA'
        ];

        $result = $addressService->resolveAddress($address,'en');
        $this->assertIsArray($result);
        $this->assertEquals([
            "message" => "The line1 field is required.",
            "filed" => "Address line1",
            "data" => [
                "line1" => "The line1 field is required."
            ],
            "status" => "error",
            "type" => "validate"
        ],$result);

        $address = [
            'city'       => 'Irvine',
            'country'    => 'US',
            'postalCode' => '92615',
            'region'     => 'CAD',
            'line1'      => '2000 Main Street',
        ];

        $result = $addressService->resolveAddress($address,'en');

        $this->assertIsArray($result);
        $this->assertEquals([
            "message" => "invalid shipping address",
            "filed" => "Address postalCode",
            "data" => [],
            "status" => "error",
            "type" => "validate"
        ],$result);

        $address = [
            'city'       => 'Irvine',
            'country'    => 'US',
            'postalCode' => '92615',
            'region'     => 'CA',
            'line1'      => '123 Main Street',
        ];

        $result = $addressService->resolveAddress($address,'en');
        $this->assertIsArray($result);

        $this->assertEquals(false,$result['status']);
        $this->assertEquals('default',$result['type']);

        $address = [
            'city'       => 'Irvine',
            'country'    => 'US',
            'postalCode' => '926151',
            'region'     => 'CA',
            'line1'      => '2000 Main Street',
        ];

        $result = $addressService->resolveAddress($address,'en');

        $this->assertEquals(false,$result['status']);
        $this->assertEquals('validate',$result['type']);

    }

}