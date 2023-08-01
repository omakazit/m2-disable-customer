<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\ViewModel;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Omakazit\DisableCustomer\Api\CustomerAccountInterface;

class PersonalInfo implements ArgumentInterface
{
    public function __construct(
        private readonly CustomerAccountInterface $customerAccount
    ) {
    }

    public function getDisableStatus(CustomerInterface $customer): string
    {
        $isDisabled = 'No';

        if ($this->customerAccount->isDisabled($customer)) {
            $isDisabled = 'Yes';
        }

        return $isDisabled;
    }
}
