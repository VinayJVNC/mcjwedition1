<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Model\ResourceModel\Item;

/**
 * RMA entity collection
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Eav\Model\Entity\Collection\AbstractCollection
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\Rma\Model\Item', 'Magento\Rma\Model\ResourceModel\Item');
    }

    /**
     * Add rma filter
     *
     * @param int $rmaEntityId
     * @return $this
     */
    public function setOrderFilter($rmaEntityId)
    {
        $this->addAttributeToFilter('rma_entity_id', $rmaEntityId);
        return $this;
    }
}
