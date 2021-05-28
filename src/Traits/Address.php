<?php

namespace Smbear\Avatax\Traits;

use Smbear\Avatax\Exceptions\ConfigException;
use Smbear\Avatax\Exceptions\ParamsException;

trait Address
{
    public $address = null;

    public $fromAddress = null;

    /**
     * @Notes:设置收获地址
     *
     * @param array $params
     * @return object
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 18:10
     */
    public function setAddress(array $params = []) : object
    {
        $params = avatax_format_params($params);

        $this->address = [
            'line1'      => $params['line1'] ?? '',
            'line2'      => $params['line2'] ?? '',
            'line3'      => $params['line3'] ?? '',
            'city'       => $params['city'] ?? '',
            'region'     => $params['region'] ?? '',
            'postalCode' => $params['postalCode'] ?? '',
            'country'    => $params['country'] ?? '',
            'textCase'   => $params['textCase'] ?? ''
        ];

        return $this;
    }

    /**
     * @Notes:获取到收获地址
     *
     * @return null
     * @throws ParamsException
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 10:17
     */
    public function getAddress()
    {
        if (is_null($this->address)){
            throw new ParamsException('address 参数异常，请先通过 setAddress 设置参数');
        }

        return $this->address;
    }

    /**
     * @Notes:获取发货地址
     *
     * @return array
     * @throws ConfigException
     * @Author: smile
     * @Date: 2021/5/26
     * @Time: 17:24
     */
    public function getFromAddress() : array
    {
        if (is_null($this->fromAddress)){
            $fromAddress = config('avatax.shipFromAddress');

            if (!avatax_check_array_value($fromAddress)){
                throw new ConfigException('配置文件 shipFromAddress 参数数据异常');
            }

            $params = avatax_format_params($fromAddress);

            $this->fromAddress = [
                'line1'      => $params['line1'] ?? '',
                'line2'      => $params['line2'] ?? '',
                'line3'      => $params['line3'] ?? '',
                'city'       => $params['city'] ?? '',
                'region'     => $params['region'] ?? '',
                'postalCode' => $params['postalCode'] ?? '',
                'country'    => $params['country'] ?? '',
                'textCase'   => $params['textCase'] ?? ''
            ];
        }

        return $this->fromAddress;
    }
}
