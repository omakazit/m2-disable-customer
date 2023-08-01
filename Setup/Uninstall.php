<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->getConnection()->dropColumn(
            $setup->getTable('is_disabled'),
            'customer_entity'
        );
    }
}
