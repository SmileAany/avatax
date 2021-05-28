<?php

namespace Smbear\Avatax\Services;

use Smbear\Avatax\Enums\AvataxEnums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Smbear\Avatax\Exceptions\AvataxException;
use Smbear\Avatax\Exceptions\ConfigException;

class AvataxAddressService
{
    public $clientService;

    public function __construct()
    {
        $this->clientService = new AvataxClientService();
    }

    /**
     * @Notes:解析地址
     *
     * @param array $address
     * @param string $local
     * @return array
     * @throws ConfigException
     * @throws AvataxException
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 15:48
     */
    public function resolveAddress(array $address,string $local): array
    {
        $ruleResult  = $this->rulesResolveAddress($address,$local);

        if (!is_null($ruleResult)){
            foreach ($ruleResult as $key => $value){
                return avatax_address_return($value,'Address '.$key,$ruleResult,'error',AvataxEnums::ADDRESS_ERROR_TYPE_VALIDATE);
            }
        }

        if (!$this->localResolveAddress($address)){
            return avatax_address_return('invalid shipping address','Address postalCode',[],'error',AvataxEnums::ADDRESS_ERROR_TYPE_VALIDATE);
        }

        return $this->ApiResolveAddress($address,$local);

    }

    /**
     * @Notes: api验证接口
     *
     * @param array $address
     * @param string $local
     * @return array
     * @throws ConfigException
     * @throws AvataxException
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 15:48
     */
    public function ApiResolveAddress(array $address,string $local): array
    {
        $response = $this
            ->clientService
            ->getClient()
            ->resolveAddressPost($address);

        if (is_string($response)){
            return avatax_address_return($response,'',[],false,'network');
        }

        if (is_object($response)){
            $message = avatax_get_trans('error',$local);

            if (isset($response->messages) && is_object($response->messages[0]) && $response->messages[0]->severity == 'Error') {
                $message = $response->messages[0]->details ?: avatax_get_trans('validated',$local);

                return avatax_address_return($message,$response->messages[0]->refersTo,[],false,AvataxEnums::ADDRESS_ERROR_TYPE_DEFAULT);
            }

            $resolutionQuality = $response->resolutionQuality ?? '';

            if (!empty($resolutionQuality) && in_array($resolutionQuality,AvataxEnums::RESOLUTION_QUALITY_VALIDATE)){
                return avatax_address_return($message,'',[],false,AvataxEnums::ADDRESS_ERROR_TYPE_DEFAULT);
            }

            if (!empty($resolutionQuality) && $resolutionQuality == 'Intersection'){
                $status = $this->verifyValidatedAddresses($response);

                if ($status == false){
                    $message = $message .$this->getValidatedAddresses($response->validatedAddresses);

                    return avatax_address_return($message,'',[],false,AvataxEnums::ADDRESS_ERROR_TYPE_VALIDATE);
                }
            }

            if (isset($response->validatedAddresses[0]) && is_object($response->validatedAddresses[0]) && $response->validatedAddresses[0]->addressType == 'UnknownAddressType'){
                return avatax_address_return($message,'',[],false,AvataxEnums::ADDRESS_ERROR_TYPE_DEFAULT);
            }

            return avatax_address_return('success',[],'',true);
        }

        return avatax_address_return('未知错误','',[],false,AvataxEnums::ADDRESS_ERROR_TYPE_UNKNOWN);
    }

    /**
     * @Notes:有效地址审核对比,当城市，省，邮编不对的情况下，需要提示用户
     *
     * @param $response
     * @return bool
     * @Author: smile
     * @Date: 2021/5/19
     * @Time: 16:40
     */
    private function verifyValidatedAddresses($response) : bool
    {
        $validatedAddresses = current($response->validatedAddresses);

        $conditions = [
            'region'     => 'string',
            'city'       => 'string',
            'postalCode' => 'int'
        ];

        foreach ($conditions as $key => $value){
            $addressValue = trim($response->address->$key ?? '');

            $validatedAddressesValue = trim($validatedAddresses->$key ?? '');

            if ($value == 'string') {
                if (strtolower($addressValue) != strtolower($validatedAddressesValue)) {
                    return false;
                }
            } else {
                if ((int) $addressValue != (int) $validatedAddressesValue){
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @Notes:获取到有效的地址
     *
     * @param array $validatedAddress
     * @return string
     * @Author: smile
     * @Date: 2021/5/19
     * @Time: 15:47
     */
    public function getValidatedAddresses(array $validatedAddress): string
    {
        $validatedAddress = current($validatedAddress);

        $address = [
            $validatedAddress->line1,
        ];

        if (!empty($validatedAddress->line2)){
            $address = [
                $validatedAddress->line1.','.$validatedAddress->line2
            ];
        }

        $address = array_merge($address,[
            $validatedAddress->city,
            (int) $validatedAddress->postalCode,
            $validatedAddress->region
        ]);

        return implode(",",$address);
    }

    /**
     * @Notes:本地验证
     *
     * @param array $address
     * @return bool
     * @throws ConfigException
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 14:22
     */
    public function localResolveAddress(array $address): bool
    {
        $modelName = config('avatax.model');

        if (empty($modelName)){
            throw new ConfigException('配置文件 model 参数异常');
        }

        try{
            $model = new $modelName();

            if (!$model instanceof Model){
                throw new ConfigException('配置文件 model 参数异常');
            }

            $statesCode = $model->where('zip',$address['postalCode'])
                ->value('states_code');

            if (!empty($statesCode) && $statesCode != $address['region']){
                return false;
            }

            return true;
        }catch (\Exception $exception){
            throw new ConfigException('配置文件 model 参数异常');
        }
    }

    /**
     * @Notes:规则验证
     *
     * @param array $address
     * @param string $local
     * @return array|null
     * @Author: smile
     * @Date: 2021/5/27
     * @Time: 14:22
     */
    public function rulesResolveAddress(array $address,string $local): ?array
    {
        $validator = Validator::make($address, [
            'line1'      => 'bail|required|max:50',
            'line2'      => 'bail|nullable|max:100',
            'region'     => 'bail|required',
            'country'    => 'bail|required',
            'postalCode' => 'bail|required|max:11',
            'city'       => 'bail|required|max:50',
        ],[
            'country.required'    => avatax_get_trans('address_rule_error_country_required',$local),
            'postalCode.required' => avatax_get_trans('address_rule_error_postal_code_required',$local),
            'postalCode.max'      => avatax_get_trans('address_rule_error_postal_code_max',$local),
            'line1.max'           => avatax_get_trans('address_rule_error_line1_max',$local),
            'line2.max'           => avatax_get_trans('address_rule_error_line2_max',$local),
            'city.required'       => avatax_get_trans('address_rule_error_city_required',$local),
            'city.max'            => avatax_get_trans('address_rule_error_city_max',$local),
            'region.required'     => avatax_get_trans('address_rule_error_region_required',$local)
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();

            $result = $errors->toArray();

            array_walk($result,function(&$value){
                $value = current($value);
            });

            return $result;
        }

        return null;
    }
}