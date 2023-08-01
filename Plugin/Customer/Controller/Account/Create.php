<?php

declare(strict_types=1);

namespace Omakazit\DisableCustomer\Plugin\Customer\Controller\Account;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Omakazit\DisableCustomer\Helper\Config;

class Create
{
    public function __construct(
        private readonly Config $config,
        private readonly UrlInterface $urlBuilder
    ) {
    }

    public function beforeDispatch(
        ActionInterface $subject,
        RequestInterface $request
    ) {
        if ($this->config->isEnabled() && $this->config->isCustomerRegistrationEnabled() === false) {
            /** @var \Magento\Framework\HTTP\PhpEnvironment\Response $response */
            $response = $subject->getResponse();
            $redirectUrl = $this->urlBuilder->getUrl('customer/account/login');

            return $response->setRedirect($redirectUrl)->sendResponse();
        }

        return [$request];
    }
}
