<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Model\Plugin;

class OrderAddressName
{
    /**
     * @param \Magento\Sales\Model\Order\Address $subject
     * @param \Closure $proceed
     * @return \Magento\Framework\Phrase
     */
    public function aroundGetName(
        \Magento\Sales\Model\Order\Address $subject,
        \Closure $proceed
    ) {
        $result = $proceed();

        if ($subject->getGiftregistryItemId()) {
            $result = __("Ship to the recipient's address.");
        }

        return $result;
    }
}
