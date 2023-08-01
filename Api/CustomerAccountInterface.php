<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Api;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerAccountInterface
{
    public const ACCOUNT_DISABLED = '1';

    public const IS_DISABLED_ATTR_CODE = 'is_disabled';

    public function isDisabled(CustomerInterface $customer): bool;
}
