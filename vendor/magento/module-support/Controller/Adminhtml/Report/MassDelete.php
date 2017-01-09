<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Controller\Adminhtml\Report;

use Magento\Support\Controller\Adminhtml\AbstractMassDelete;
use Magento\Framework\Controller\ResultFactory;

/**
 * Mass Delete action for reports
 */
class MassDelete extends AbstractMassDelete
{
    /**
     * Field id
     */
    const ID_FIELD = 'report_id';

    /**
     * Resource collection
     *
     * @var string
     */
    protected $collection = 'Magento\Support\Model\ResourceModel\Report\Collection';

    /**
     * @var string
     */
    protected $model = 'Magento\Support\Model\Report';

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $selected = $this->getRequest()->getParam('selected');
        $excluded = $this->getRequest()->getParam('excluded');

        try {
            $this->processItems($selected, $excluded);
        } catch (\Exception $e) {
            $this->messageManager->addException(
                $e,
                __('An error occurred during mass deletion of system reports. Please review log and try again.')
            );
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath(static::REDIRECT_URL);
    }
}
