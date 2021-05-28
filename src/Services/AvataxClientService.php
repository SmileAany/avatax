<?php

namespace Smbear\Avatax\Services;

use Exception;
use Avalara\AvaTaxClient;
use Smbear\Avatax\Exceptions\AvataxException;
use Smbear\Avatax\Exceptions\ConfigException;

class AvataxClientService
{
    public $client = null;

    /**
     * @Notes:核对client config 参数
     *
     * @return array
     * @throws ConfigException
     * @Author: smile
     * @Date: 2021/5/26
     * @Time: 17:55
     */
    public function checkClientConfig() : array
    {
        $data = [
            'appName'     => avatax_get_config_value('appName'),
            'appVersion'  => avatax_get_config_value('appVersion'),
            'machineName' => avatax_get_config_value('machineName'),
            'accountId'   => avatax_get_config_value('accountId'),
            'licenseKey'  => avatax_get_config_value('licenseKey'),
            'environment' => config('avatax.environment')
        ];

        if (!avatax_check_array_value($data)){
            throw new ConfigException('配置文件'.config('avatax.environment').' 参数数据异常');
        }

        return $data;
    }

    /**
     * @Notes:
     *
     * @param array $guzzleParams
     * @return AvaTaxClient|null
     * @throws AvataxException
     * @throws ConfigException
     * @throws Exception
     * @Author: smile
     * @Date: 2021/5/26
     * @Time: 17:55
     */
    public function getClient(array $guzzleParams = []): ?AvaTaxClient
    {
        if (is_null($this->client)){
            $clientParams = $this->checkClientConfig();

            $client = new AvaTaxClient(
                $clientParams['appName'],
                $clientParams['appVersion'],
                $clientParams['machineName'],
                $clientParams['environment'],
                $guzzleParams
            );

            $client->withLicenseKey(
                $clientParams['accountId'],
                $clientParams['licenseKey'],
            );

            $ping = $client->ping();

            if (is_object($ping) && isset($ping->authenticated) && $ping->authenticated == 'true'){
                $this->client = $client;

                return $this->client;
            }

            throw new AvataxException('client 连接失败');
        }

        return $this->client;
    }
}