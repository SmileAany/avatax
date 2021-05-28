<?php

namespace Smbear\Avatax\Traits;

use Smbear\Avatax\Enums\AvataxEnums;

trait Order
{
    public $orders = null;

    /**
     * @Notes:设置订单
     *
     * @param array $params
     * @return object
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 18:11
     */
    public function setOrder(array $params = []) : object
    {
        $params = avatax_format_params($params);

        $this->orders = [
            'code'                  => $params['documentCode'] ?? '',
            'customerCode'          => $params['customerCode'] ?? 0,
            'entityUseCode'         => $params['customerCode'] ?? 0,
            'currencyCode'          => $params['currencyCode'] ?? AvataxEnums::CURRENCY_CODE,
            'exchangeRate'          => $params['exchangeRate'] ?? AvataxEnums::EXCHANGE_RATE,
            'description'           => $params['description']  ?? '',
            'purchaseOrderNo'       => $params['purchaseOrderNo'] ?? '',
            'salespersonCode'       => $params['salespersonCode'] ?? AvataxEnums::ADMIN_ID,
            'lines'                 => $params['lines'] ?? []
        ];

        return $this;
    }

    /**
     * @Notes:获取订单
     *
     * @return null
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 18:11
     */
    public function getOrder()
    {
        if (is_null($this->orders)){
            throw new ParamsException('orders 参数异常，请先通过 setOrder 设置参数');
        }

        return $this->orders;
    }
}