<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedSalesRule\Model\Rule\Condition\ConcreteCondition\Address;

use Magento\AdvancedSalesRule\Model\Rule\Condition\ConcreteCondition\AbstractFilterableCondition;

class Postcode extends AbstractFilterableCondition
{
    const FILTER_TEXT_GENERATOR_CLASS =
        'Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Address\Postcode';

    /**
     * @return string
     */
    protected function getFilterTextPrefix()
    {
        return self::FILTER_TEXT_PREFIX;
    }

    /**
     * @return string
     */
    protected function getFilterTextGeneratorClass()
    {
        return self::FILTER_TEXT_GENERATOR_CLASS;
    }
}
