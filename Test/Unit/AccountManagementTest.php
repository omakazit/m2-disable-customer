<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Test\Unit;

use Magento\Customer\Api\AccountManagementInterface as MagentoAccountManagement;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Omakazit\DisableCustomer\Helper\Config;
use Omakazit\DisableCustomer\Plugin\Customer\Model\AccountManagement;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use function __;

class AccountManagementTest extends TestCase
{
    public function testCustomerAccountIsDisabled(): void
    {
        $config = $this->createMock(Config::class);
        $config->method('isEnabled')->willReturn(true);
        $config->method('isDisabledAccountEnabled')->willReturn(true);
        $config->method('getErrorMessage')->willReturn('Account Disabled');

        $attribute = $this->getMockForAbstractClass(AttributeInterface::class);
        $attribute->method('getValue')->willReturn('1');

        $magentoAccountManagement = $this->getMockForAbstractClass(MagentoAccountManagement::class);

        $customer = $this->getMockForAbstractClass(CustomerInterface::class);
        $customer->method('getCustomAttribute')->with('is_disabled')->willReturn($attribute);

        $customerRepository = $this->getMockForAbstractClass(CustomerRepositoryInterface::class);
        $customerRepository->method('get')->willReturn($customer);

        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $request = $this->getMockForAbstractClass(RequestInterface::class);

        $accountManagement = new AccountManagement(
            $customerRepository,
            $config,
            $logger,
            $request
        );

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Account Disabled');
        $accountManagement->beforeAuthenticate($magentoAccountManagement, 'test@test.com', 'password');
    }

    public function testCanCatchCustomerRepositoryException(): void
    {
        $config = $this->createMock(Config::class);
        $config->method('isEnabled')->willReturn(true);
        $config->method('isDisabledAccountEnabled')->willReturn(true);

        $attribute = $this->getMockForAbstractClass(AttributeInterface::class);
        $attribute->method('getValue')->willReturn('1');

        $magentoAccountManagement = $this->getMockForAbstractClass(MagentoAccountManagement::class);
        $invalidEmailOrPasswordException = new InvalidEmailOrPasswordException(__('Invalid login or password.'));

        $customer = $this->getMockForAbstractClass(CustomerInterface::class);
        $customer->method('getCustomAttribute')->with('is_disabled')->willReturn($attribute);

        $customerRepository = $this->getMockForAbstractClass(CustomerRepositoryInterface::class);
        $customerRepository->method('get')->willReturn($customer);
        $customerRepository->method('get')->willThrowException($invalidEmailOrPasswordException);

        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $request = $this->getMockForAbstractClass(RequestInterface::class);

        $accountManagement = new AccountManagement(
            $customerRepository,
            $config,
            $logger,
            $request
        );

        $this->expectException(InvalidEmailOrPasswordException::class);
        $this->expectExceptionMessage('Invalid login or password.');
        $accountManagement->beforeAuthenticate($magentoAccountManagement, 'test@test.com', 'password');
    }

    public function testCustomerAccountIsEnabled(): void
    {
        $config = $this->createMock(Config::class);
        $config->method('isEnabled')->willReturn(true);
        $config->method('isDisabledAccountEnabled')->willReturn(true);
        $config->method('getErrorMessage')->willReturn('Account Disabled');

        $attribute = $this->getMockForAbstractClass(AttributeInterface::class);
        $attribute->method('getValue')->willReturn('0');

        $magentoAccountManagement = $this->getMockForAbstractClass(MagentoAccountManagement::class);

        $customer = $this->getMockForAbstractClass(CustomerInterface::class);
        $customer->method('getCustomAttribute')->with('is_disabled')->willReturn($attribute);

        $customerRepository = $this->getMockForAbstractClass(CustomerRepositoryInterface::class);
        $customerRepository->method('get')->willReturn($customer);

        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $request = $this->getMockForAbstractClass(RequestInterface::class);

        $accountManagement = new AccountManagement(
            $customerRepository,
            $config,
            $logger,
            $request
        );

        $accountManagement->beforeAuthenticate($magentoAccountManagement, 'test@test.com', 'password');

        $this->assertTrue(true);
    }

    public function testCustomerAccountIsDisabledWithExtensionDisabled(): void
    {
        $config = $this->createMock(Config::class);
        $config->method('isEnabled')->willReturn(false);
        $config->method('isDisabledAccountEnabled')->willReturn(true);
        $config->method('getErrorMessage')->willReturn('Account Disabled');

        $attribute = $this->getMockForAbstractClass(AttributeInterface::class);
        $attribute->method('getValue')->willReturn('1');

        $magentoAccountManagement = $this->getMockForAbstractClass(MagentoAccountManagement::class);

        $customer = $this->getMockForAbstractClass(CustomerInterface::class);
        $customer->method('getCustomAttribute')->with('is_disabled')->willReturn($attribute);

        $customerRepository = $this->getMockForAbstractClass(CustomerRepositoryInterface::class);
        $customerRepository->method('get')->willReturn($customer);

        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $request = $this->getMockForAbstractClass(RequestInterface::class);

        $accountManagement = new AccountManagement(
            $customerRepository,
            $config,
            $logger,
            $request
        );

        $accountManagement->beforeAuthenticate($magentoAccountManagement, 'test@test.com', 'password');

        $this->assertTrue(true);
    }
}
