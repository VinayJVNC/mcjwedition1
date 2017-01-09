<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Test\Unit\Observer;

class BindRelatedBannersToCatalogRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Banner\Observer\BindRelatedBannersToCatalogRule
     */
    protected $bindRelatedBannersToCatalogRuleObserver;

    /**
     * @var \Magento\Framework\Event\Observer
     */
    protected $eventObserver;

    /**
     * @var \Magento\Banner\Model\ResourceModel\BannerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $bannerFactory;

    /**
     * @var \Magento\Framework\Event|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $event;

    /**
     * @var \Magento\Framework\App\Request\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $http;

    protected function setUp()
    {
        $this->bannerFactory = $this->getMockBuilder('Magento\Banner\Model\ResourceModel\BannerFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->bindRelatedBannersToCatalogRuleObserver = new \Magento\Banner\Observer\BindRelatedBannersToCatalogRule(
            $this->bannerFactory
        );
    }

    /**
     * @param [] $banners
     *
     * @dataProvider testBindRelatedBannersDataProvider
     */
    public function testBindRelatedBannersToCatalogRule($banners)
    {
        $this->event = $this->getMockBuilder('Magento\Framework\Event')
            ->disableOriginalConstructor()
            ->setMethods(['getRule', 'getId'])
            ->getMock();
        $this->http = $this->getMockBuilder('Magento\Framework\App\Request\Http')
            ->disableOriginalConstructor()
            ->setMethods(['getRelatedBanners', 'getId'])
            ->getMock();
        $banner = $this->getMockBuilder('Magento\Banner\Model\ResourceModel\Banner')
            ->disableOriginalConstructor()
            ->setMethods(['bindBannersToCatalogRule'])
            ->getMock();
        $banner->expects($this->once())->method('bindBannersToCatalogRule')->with(1, $banners)->will(
            $this->returnSelf()
        );
        $this->event->expects($this->any())->method('getRule')->will($this->returnValue($this->http));
        $this->http->expects($this->once())->method('getId')->will($this->returnValue(1));
        $this->http->expects($this->any())->method('getRelatedBanners')->will(
            $this->returnValue($banners)
        );
        $this->eventObserver = $this->getMockBuilder('Magento\Framework\Event\Observer')
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventObserver->expects($this->any())->method('getEvent')->will($this->returnValue($this->event));
        $this->bannerFactory->expects($this->once())->method('create')->will($this->returnValue($banner));
        $this->assertInstanceOf(
            '\Magento\Banner\Observer\BindRelatedBannersToCatalogRule',
            $this->bindRelatedBannersToCatalogRuleObserver->execute($this->eventObserver)
        );
    }

    public function testBindRelatedBannersDataProvider()
    {
        return [
            [
                [],
            ],
            [
                'banner1',
                'banner2'
            ]
        ];
    }
}
