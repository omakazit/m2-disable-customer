<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Plugin\Customer\Model;

use Magento\Customer\Api\AccountManagementInterface as MagentoAccountManagement;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Omakazit\DisableCustomer\Helper\Config;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\State;

use function __;

class AccountManagement
{
    private const CUSTOMER_ROUTE_NAME = 'customer';

    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly Config $config,
        private readonly LoggerInterface $logger,
        private readonly RequestInterface $request
    ) {
    }

    /**
     * @throws \Magento\Framework\Exception\InvalidEmailOrPasswordException|\Magento\Framework\Exception\LocalizedException
     */
    public function beforeAuthenticate(
        MagentoAccountManagement $subject,
        string $email,
        string $password
    ): void {
        if (
            $this->config->isEnabled()
            && $this->config->isDisabledAccountEnabled()
        ) {
            try {
                $customer = $this->customerRepository->get($email);
            } catch (NoSuchEntityException $e) {
                if ($this->config->isDebug()) {
                    $this->logger->info($e->getMessage());
                }

                throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
            }

            if ($customer->getCustomAttribute('is_disabled')->getValue() === '1') {
                if ($this->config->isDebug()) {
                    $this->logger->info('Customer account is disabled', [
                        'customer_id' => $customer->getId(),
                        'is_disabled' => $customer->getCustomAttribute('is_disabled')->getValue(),
                    ]);
                }

                throw new LocalizedException(__($this->config->getErrorMessage()));
            }
        }
    }

    public function beforeCreateAccount(
        MagentoAccountManagement $subject,
        CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    ): void {
        if (
            $this->request->getRouteName() === self::CUSTOMER_ROUTE_NAME
            && $this->config->isEnabled()
            && $this->config->isCustomerRegistrationEnabled() === false
        ) {
            throw new LocalizedException(
                __('Account creation is not currently allowed.')
            );
        }
    }
}
