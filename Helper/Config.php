<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const IS_MODULE_ENABLED_PATH = 'disable_customer/general/enabled';
    private const IS_DEBUG_ENABLED_PATH = 'disable_customer/general/debug';
    private const IS_DISABLED_ACCOUNT_ENABLED = 'disable_customer/customer_disable_account/enable';
    private const ERROR_MSG_PATH = 'disable_customer/customer_disable_account/error_message';
    private const IS_CUSTOMER_ACCOUNT_REGISTRATION_ENABLED = 'disable_customer/customer_account_registration/enabled';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(int|null $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_MODULE_ENABLED_PATH,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    public function isDebug(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_DEBUG_ENABLED_PATH
        );
    }

    public function isDisabledAccountEnabled(int|null $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_DISABLED_ACCOUNT_ENABLED,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    public function getErrorMessage(int|null $scopeCode = null): string
    {
        return $this->scopeConfig->getValue(
            self::ERROR_MSG_PATH,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    public function isCustomerRegistrationEnabled(int|null $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_CUSTOMER_ACCOUNT_REGISTRATION_ENABLED,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }
}
