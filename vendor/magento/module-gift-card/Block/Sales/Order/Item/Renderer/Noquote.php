<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCard\Block\Sales\Order\Item\Renderer;

class Noquote extends \Magento\GiftCard\Block\Sales\Order\Item\Renderer
{
    /**
     * Prepare custom option for display, returns false if there's no value
     *
     * @param string $code
     * @return mixed
     */
    protected function _prepareCustomOption($code)
    {
        if ($option = $this->getOrderItem()->getProductOptionByCode($code)) {
            return $option;
        }
        return false;
    }

    /**
     * Prepare a string containing name and email
     *
     * @param string $name
     * @param string $email
     * @return string
     */
    protected function _getNameEmailString($name, $email)
    {
        return "{$name} <{$email}>";
    }
}
