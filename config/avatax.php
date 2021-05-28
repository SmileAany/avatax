<?php

return [
    'environment' => env('AVATAX_ENVIRONMENT','sandbox'),

    'model'       => env('AVATAX_MODEL',''),

    'channel'     => env('AVATAX_CHANNEL','avatax'),

    'shipFromAddress' => [
        'line1'      => env('AVATAX_SHIP_FROM_ADDRESS_LINE1','') ,
        'city'       => env('AVATAX_SHIP_FROM_ADDRESS_CITY',''),
        'country'    => env('AVATAX_SHIP_FROM_ADDRESS_COUNTRY',''),
        'postalCode' => env('AVATAX_SHIP_FROM_ADDRESS_POSTAL_CODE',''),
        'region'     => env('AVATAX_SHIP_FROM_ADDRESS_REGION','')
    ],

    'sandbox'     => [
        'appName'      => env('AVATAX_SANDBOX_APP_NAME',''),
        'appVersion'   => env('AVATAX_SANDBOX_APP_VERSION',''),
        'machineName'  => env('AVATAX_SANDBOX_MACHINE_NAME',''),
        'accountId'    => env('AVATAX_SANDBOX_ACCOUNT_ID',''),
        'licenseKey'   => env('AVATAX_SANDBOX_LICENSE_KEY',''),
        'companyCode'  => env('AVATAX_SANDBOX_COMPANY_CODE','')
    ],

    'production'  => [
        'appName'      => env('AVATAX_PRODUCTION_APP_NAME',''),
        'appVersion'   => env('AVATAX_PRODUCTION_APP_VERSION',''),
        'machineName'  => env('AVATAX_PRODUCTION_MACHINE_NAME',''),
        'accountId'    => env('AVATAX_PRODUCTION_ACCOUNT_ID',''),
        'licenseKey'   => env('AVATAX_PRODUCTION_LICENSE_KEY',''),
        'companyCode'  => env('AVATAX_PRODUCTION_COMPANY_CODE','')
    ]
];
