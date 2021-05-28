<?php

namespace Smbear\Avatax\Enums;

class AvataxEnums
{
    const ALLOW_TYPES = [
        'SalesOrder',
        'SalesInvoice'
    ];

    const ADDRESS_ERROR_TYPE_VALIDATE = 'validate';

    const ADDRESS_ERROR_TYPE_DEFAULT = 'default';

    const ADDRESS_ERROR_TYPE_UNKNOWN = 'unknown';

    const RESOLUTION_QUALITY_VALIDATE = [
        'External','NotCoded'
    ];

    const CURRENCY_CODE = 'USD';

    const EXCHANGE_RATE = 1;

    const CURRENCY_VALUE = 1;

    const ADMIN_ID = 0;

    const TZ = 'America/Los_Angeles';
}