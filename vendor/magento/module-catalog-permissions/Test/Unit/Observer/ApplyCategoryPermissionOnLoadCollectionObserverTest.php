<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogPermissions\Test\Unit\Observer;

use Magento\CatalogPermissions\Observer\ApplyCategoryPermissionOnLoadCollectionObserver;
use Magento\Framework\DataObject;

/**
 * Test for \Magento\CatalogPermissions\Observer\ApplyCategoryPermissionOnLoadCollectionObserver
 */
class ApplyCategoryPermissionOnLoadCollectionObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\CatalogPermissions\Observer\ApplyCategoryPermissionOnLoadCollectionObserver
     */
    protected $observer;

    /**
     * @var \Magento\CatalogPermissions\App\ConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $permissionsConfig;

    /**
     * @var \Magento\CatalogPermissions\Model\Permission\Index|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $permissionIndex;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Event\Observer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventObserverMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->storeManager = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionsConfig = $this->getMock('Magento\CatalogPermissions\App\ConfigInterface');
        $this->permissionIndex = $this->getMock('Magento\CatalogPermissions\Model\Permission\Index', [], [], '', false);

        $this->eventObserverMock = $this->getMockBuilder('Magento\Framework\Event\Observer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->observer = new ApplyCategoryPermissionOnLoadCollectionObserver(
            $this->permissionsConfig,
            $this->storeManager,
            $this->getMock('Magento\Customer\Model\Session', [], [], '', false),
            $this->permissionIndex,
            $this->getMock('Magento\CatalogPermissions\Observer\ApplyPermissionsOnCategory', [], [], '', false)
        );
    }

    /**
     * @return void
     */
    public function testApplyCategoryPermissionOnLoadCollection()
    {
        $categoryCollection = $this->getMockBuilder('Magento\Framework\DataObject')
            ->disableOriginalConstructor()
            ->setMethods(['getColumnValues', 'getCategoryCollection', 'getItemById'])
            ->getMock();

        $categoryCollection
            ->expects($this->atLeastOnce())
            ->method('getColumnValues')
            ->with('entity_id')
            ->willReturn([1, 2, 3]);
        $categoryCollection
            ->expects($this->once())
            ->method('getItemById')
            ->with(123)
            ->willReturn(new DataObject());

        $this->permissionsConfig
            ->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);

        $this->storeManager
            ->expects($this->any())
            ->method('getStore')
            ->willReturn(new DataObject(['website_id' => 123]));

        $this->eventObserverMock
            ->expects($this->any())
            ->method('getEvent')
            ->willReturn(new DataObject(['category_collection' => $categoryCollection]));

        $this->permissionIndex
            ->expects($this->once())
            ->method('getIndexForCategory')
            ->with([1, 2, 3], $this->anything(), $this->anything())
            ->willReturn([123 => 987]);

        $this->observer->execute($this->eventObserverMock);
    }
}
