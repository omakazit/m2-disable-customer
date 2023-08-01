<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Omakazit\DisableCustomer\Helper\Config;

class RemoveCustomerRegistration implements ObserverInterface
{
    private const BLOCK_TO_REMOVE = [
        'customer.new',
        'register-link',
        'register.customer.link',
    ];

    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function execute(Observer $observer): void
    {
        if ($this->config->isEnabled() && $this->config->isCustomerRegistrationEnabled() === false) {
            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $observer->getData('layout');

            foreach (self::BLOCK_TO_REMOVE as $blockName) {
                $block = $layout->getBlock($blockName);

                if ($block !== false) {
                    $layout->unsetElement($blockName);
                }
            }
        }
    }
}
