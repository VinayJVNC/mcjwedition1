<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Unit\Controller\Adminhtml\Category\Update;

use Magento\CatalogStaging\Controller\Adminhtml\Category\Update\Delete as DeleteController;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    /** @var DeleteController */
    protected $controller;

    /** @var \Magento\Backend\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject */
    protected $context;

    /** @var \Magento\Staging\Model\Entity\Update\Delete|\PHPUnit_Framework_MockObject_MockObject */
    protected $stagingUpdateDelete;

    /** @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    public function setUp()
    {
        $this->context = $this->getMockBuilder('Magento\Backend\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $this->request = $this->getMockBuilder('Magento\Framework\App\RequestInterface')
            ->getMock();
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->stagingUpdateDelete = $this->getMockBuilder('Magento\Staging\Model\Entity\Update\Delete')
            ->disableOriginalConstructor()
            ->getMock();
        $this->controller = new DeleteController($this->context, $this->stagingUpdateDelete);
    }

    public function testExecute()
    {
        $categoryId = 1;
        $updateId = 2;
        $staging = [];
        $this->request->expects($this->exactly(3))
            ->method('getParam')
            ->withConsecutive(
                ['id'],
                ['update_id'],
                ['staging']
            )
            ->willReturnOnConsecutiveCalls(
                $categoryId,
                $updateId,
                $staging
            );
        $this->stagingUpdateDelete
            ->expects($this->once())
            ->method('execute')
            ->with([
                'entityId' => $categoryId,
                'updateId' => $updateId,
                'stagingData' => $staging
            ])
            ->willReturn(true);
        $this->assertTrue($this->controller->execute());
    }
}