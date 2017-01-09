<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Action;

/**
 * Class Rows
 */
class Rows extends \Magento\AdvancedSalesRule\Model\Indexer\SalesRule\AbstractAction
{
    /**
     * Refresh entities index
     *
     * @param int[] $entityIds
     * @return $this
     */
    public function execute(array $entityIds = [])
    {
        $this->setActionIds($entityIds);
        $this->reindex(false);
        return $this;
    }
}
