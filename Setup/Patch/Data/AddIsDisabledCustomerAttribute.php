<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddIsDisabledCustomerAttribute implements DataPatchInterface
{
    private const ATTRIBUTE_CODE = 'is_disabled';

    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private CustomerSetupFactory $customerSetupFactory,
        private AttributeResource $attributeResource
    ) {
    }

    public function apply(): void
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'static',
                'label' => 'Account disabled',
                'input' => 'boolean',
                'required' => false,
                'visible' => true,
                'system' => false,
                'source' => Boolean::class,
                'default' => 0,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_used_for_customer_segment' => true,
                'note' => 'Determine whether the customer can connect to the storefront and to the API',
            ]
        );

        $isBlockedAttribute = $customerSetup->getEavConfig()->getAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_CODE
        );

        $isBlockedAttribute->addData([
            'used_in_forms' => ['adminhtml_customer'],
        ]);

        $this->attributeResource->save($isBlockedAttribute); /** @phpstan-ignore-line */
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
