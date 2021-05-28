<?php

if (!function_exists('avatax_get_config_value')){
    function avatax_get_config_value($key){
        $environment = config('avatax.environment');

        return config('avatax.'.$environment.'.'.$key);
    }
}

if (!function_exists('avatax_check_array_value')){
    function avatax_check_array_value(array $array):bool{
        foreach ($array as $key => $value){
            if (empty($value)){
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('avatax_format_params')){
    function avatax_format_params(array $params) : array{
        foreach ($params as $key => &$value){
            if (is_string($value)){
                $value = trim($value);

                if (empty($value)) unset($params[$key]);
            }
        }

        return $params;
    }
}

if (!function_exists('avatax_address_return')){
    function avatax_address_return($message = '',$filed = '',$data = [],$status = '',$type = ''):array{
        return compact('message','filed','data','status','type');
    }
}

if (!function_exists('avatax_return_success')){
    function avatax_return_success(string $message, array $data = []) : array{
        return [
            'status'  => 'success',
            'code'    => 200,
            'message' => $message,
            'data'    => $data
        ];
    }
}

if (!function_exists('avatax_return_error')){
    function avatax_return_error(string $message, array $data = []) : array{
        return [
            'status'  => 'error',
            'code'    => 500,
            'message' => $message,
            'data'    => $data
        ];
    }
}

if (!function_exists('avatax_get_trans')){
    function avatax_get_trans(string $key,string $local){
        return trans('avatax::avatax.'.$key,[],$local);
    }
}

if (!function_exists('avatax_get_save_data')){
    function avatax_get_save_data(int $userId,string $documentId,array $address,array $from,array $order,int $status,$response):array{
        return [
            'user_id'     => $userId,
            'document_id' => $documentId,
            'address'     => json_encode($address),
            'from'        => json_encode($from),
            'order'       => json_encode($order),
            'status'      => (int) $status,
            'response'    => is_string($response) ?: json_encode((array) $response)
        ];
    }
}