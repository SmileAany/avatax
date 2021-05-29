<?php

namespace Smbear\Avatax;

use Smbear\Avatax\Traits\Order;
use Smbear\Avatax\Traits\Address;
use Illuminate\Support\Facades\Log;
use Smbear\Avatax\Enums\AvataxEnums;
use Smbear\Avatax\Events\SaveDataEvent;
use Smbear\Avatax\Contracts\AvataxInterface;
use Smbear\Avatax\Exceptions\ParamsException;
use Smbear\Avatax\Exceptions\AvataxException;
use Smbear\Avatax\Services\AvataxTransService;
use Smbear\Avatax\Services\AvataxAddressService;

class Avatax implements AvataxInterface
{
    use Address,Order;

    public $local = 'en';

    public $addressService;

    public $transService;

    public function __construct()
    {
        $this->addressService = new AvataxAddressService();

        $this->transService   = new AvataxTransService();
    }

    /**
     * @Notes:设置本地语言环境
     * 默认采用en
     * 由于目前avatax只针对于en，但为后期扩展预留口子
     * @param string $local
     * @return object
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 16:13
     */
    public function setLocal($local = 'en') : object
    {
        $this->local = $local;

        return $this;
    }

    /**
     * @Notes:计算税费
     *
     * @param string $type
     * @return array
     * @throws Exceptions\AvataxException
     * @throws ParamsException|Exceptions\ConfigException
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 16:14
     */
    public function createTransaction(string $type) : array
    {
        if ( !in_array($type,AvataxEnums::ALLOW_TYPES) ){
            throw new ParamsException('type 参数错误');
        }

        $address = $this->getAddress();
        $order   = $this->getOrder();

        try{
            $addressResult = $this->addressService->resolveAddress($address,$this->local);

            if($addressResult['status'] == false){
                if ($addressResult['type'] == AvaTaxEnums::ADDRESS_ERROR_TYPE_DEFAULT){
                    $address['line1'] = 'GENERAL DELIVERY';
                } else {
                    event(new SaveDataEvent(avatax_get_save_data($order['customerCode'],$order['code'],$address,$this->getFromAddress(),$order,true,$addressResult),$type));

                    return avatax_return_error('address error',$addressResult);
                }
            }

            $transActionResult = $this->transService->transaction($type,$address,$order,$this->getFromAddress());

            event(new SaveDataEvent(avatax_get_save_data($order['customerCode'],$order['code'],$address,$this->getFromAddress(),$order,true,$transActionResult),$type));

            if (is_string($transActionResult)){
                return avatax_return_error($transActionResult);
            }

            if (is_object($transActionResult)){
                return avatax_return_success('success',(array) $transActionResult);
            }
        }catch (\Exception $exception){
            if (!$exception instanceof AvataxException){
                Log::channel(config('avatax.channel'))->info($exception);

                return avatax_return_error('api error',[]);
            }

            throw new $exception;
        }
    }
}