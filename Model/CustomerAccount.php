<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Omakazit\DisableCustomer\Api\CustomerAccountInterface;

class CustomerAccount implements CustomerAccountInterface
{
    public function isDisabled(CustomerInterface $customer): bool
    {
        return $customer->getCustomAttribute(self::IS_DISABLED_ATTR_CODE)->getValue() === self::ACCOUNT_DISABLED;
    }
}
