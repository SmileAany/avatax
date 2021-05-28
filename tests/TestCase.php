<?php

namespace Smbear\Avatax\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Smbear\Avatax\Models\Zips;

/**
 * Override the standard PHPUnit testcase with the Testbench testcase
 *
 * @see https://github.com/orchestral/testbench#usage
 */
abstract class TestCase extends OrchestraTestCase
{
    use WithFaker;

    /**
     * Include the package's service provider(s)
     *
     * @see https://github.com/orchestral/testbench#custom-service-provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Smbear\Avatax\AvataxServiceProvider::class
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @Notes:设置config参数
     *
     * @param  $app
     * @Author: smile
     * @Date: 2021/5/26
     * @Time: 18:36
     */
    public function defineEnvironment($app)
    {
        $app['config']->set('avatax.environment','sandbox');

        $app['config']->set('avatax.sandbox',[
            'appName'      => 'FSCOM',
            'appVersion'   => '1.0',
            'machineName'  => 'www.fs.com',
            'accountId'    => '2001530119',
            'licenseKey'   => '171229243A1C7AD4',
            'companyCode'  => 'DEFAULT'
        ]);

        $app['config']->set('avatax.shipFromAddress',[
            'line1'      => '380 Centerpoint Blvd',
            'city'       => 'New Castle',
            'country'    => 'US',
            'postalCode' => '19720',
            'region'     => 'DE'
        ]);

        $app['config']->set('avatax.model',Zips::class);
    }

    public function getAddress() : array
    {
        return [
            'line1'      => '380 Centerpoint Blvd',
            'city'       => 'New Castle',
            'country'    => 'US',
            'postalCode' => '19720',
            'region'     => 'DE'
        ];
    }
}