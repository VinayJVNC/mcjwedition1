<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Unit\Model\Plugin;


class InvoiceRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\CustomerBalance\Model\Plugin\InvoiceRepository
     */
    private $plugin;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $subjectMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $invoiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $extensionAttributeMock;

    protected function setUp()
    {
        $this->subjectMock = $this->getMock(
            \Magento\Sales\Model\Order\InvoiceRepository::class,
            [],
            [],
            '',
            false
        );
        $methods = ['getExtensionAttributes', 'setCustomerBalanceAmount', 'setBaseCustomerBalanceAmount'];
        $this->invoiceMock = $this->getMock(\Magento\Sales\Model\Order\Invoice::class, $methods, [], '', false);
        $this->extensionAttributeMock = $this->getMock(
            \Magento\Sales\Api\Data\InvoiceExtension::class,
            ['getCustomerBalanceAmount', 'getBaseCustomerBalanceAmount'],
            [],
            '',
            false
        );
        $this->plugin = new \Magento\CustomerBalance\Model\Plugin\InvoiceRepository();
    }

    public function testBeforeSave()
    {
        $this->invoiceMock
            ->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($this->extensionAttributeMock);
        $this->extensionAttributeMock->expects($this->once())->method('getCustomerBalanceAmount')->willReturn(10);
        $this->extensionAttributeMock->expects($this->once())->method('getBaseCustomerBalanceAmount')->willReturn(15);
        $this->invoiceMock->expects($this->once())->method('setCustomerBalanceAmount')->with(10)->willReturnSelf();
        $this->invoiceMock->expects($this->once())->method('setBaseCustomerBalanceAmount')->with(15)->willReturnSelf();
        $this->plugin->beforeSave($this->subjectMock, $this->invoiceMock);
    }
}
