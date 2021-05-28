<?php

namespace Smbear\Avatax\Tests\Unit;

use Smbear\Avatax\Tests\TestCase;
use Smbear\Avatax\Services\AvataxClientService;

class ClientTest extends TestCase
{

    /**
     * @Notes:测试 client
     *
     * @throws \Smbear\Avatax\Exceptions\AvataxException
     * @throws \Smbear\Avatax\Exceptions\ConfigException
     * @Author: smile
     * @Date: 2021/5/26
     * @Time: 18:22
     * @test
     */
    public function test_client()
    {
        $service = new AvataxClientService();

        $this->assertIsObject($service->getClient());
    }
}