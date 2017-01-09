<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogRuleStaging\Model;

use Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor;
use Magento\Staging\Model\StagingApplierInterface;

/**
 * Class CatalogRuleApplier
 */
class CatalogRuleApplier implements StagingApplierInterface
{
    /**
     * @var RuleProductProcessor
     */
    private $ruleProductProcessor;

    /**
     * CatalogRuleApplier constructor.
     *
     * @param RuleProductProcessor $ruleProductProcessor
     */
    public function __construct(RuleProductProcessor $ruleProductProcessor)
    {
        $this->ruleProductProcessor = $ruleProductProcessor;
    }

    /**
     * @param array $entityIds
     * @return void
     */
    public function execute(array $entityIds)
    {
        if (!empty($entityIds)) {
            $this->ruleProductProcessor->markIndexerAsInvalid();
        }
    }
}
