<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerCustomAttributes\Test\Unit\Observer;

class EnterpriseCustomerAttributeDeleteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAttributeDelete
     */
    protected $observer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $orderFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteFactory;

    protected function setUp()
    {
        $this->quoteFactory = $this->getMockBuilder(
            'Magento\CustomerCustomAttributes\Model\Sales\QuoteFactory'
        )->disableOriginalConstructor()->setMethods(['create'])->getMock();

        $this->orderFactory = $this->getMockBuilder(
            'Magento\CustomerCustomAttributes\Model\Sales\OrderFactory'
        )->disableOriginalConstructor()->setMethods(['create'])->getMock();

        $this->observer = new \Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAttributeDelete(
            $this->orderFactory,
            $this->quoteFactory
        );
    }

    public function testEnterpriseCustomerAttributeDelete()
    {
        $observer = $this->getMockBuilder('Magento\Framework\Event\Observer')
            ->disableOriginalConstructor()
            ->getMock();

        $event = $this->getMockBuilder('Magento\Framework\Event')
            ->setMethods(['getAttribute'])
            ->disableOriginalConstructor()
            ->getMock();

        $dataModel = $this->getMockBuilder('Magento\Customer\Model\Attribute')
            ->setMethods(['__wakeup', 'isObjectNew'])
            ->disableOriginalConstructor()
            ->getMock();

        $order = $this->getMockBuilder('Magento\CustomerCustomAttributes\Model\Sales\Order')
            ->disableOriginalConstructor()
            ->getMock();

        $quote = $this->getMockBuilder('Magento\CustomerCustomAttributes\Model\Sales\Quote')
            ->disableOriginalConstructor()
            ->getMock();

        $dataModel->expects($this->once())->method('isObjectNew')->will($this->returnValue(false));
        $observer->expects($this->once())->method('getEvent')->will($this->returnValue($event));
        $event->expects($this->once())->method('getAttribute')->will($this->returnValue($dataModel));
        $quote->expects($this->once())->method('deleteAttribute')->with($dataModel)->will($this->returnSelf());
        $this->quoteFactory->expects($this->once())->method('create')->will($this->returnValue($quote));
        $order->expects($this->once())->method('deleteAttribute')->with($dataModel)->will($this->returnSelf());
        $this->orderFactory->expects($this->once())->method('create')->will($this->returnValue($order));
        /** @var \Magento\Framework\Event\Observer $observer */

        $this->assertInstanceOf(
            'Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAttributeDelete',
            $this->observer->execute($observer)
        );
    }
}
