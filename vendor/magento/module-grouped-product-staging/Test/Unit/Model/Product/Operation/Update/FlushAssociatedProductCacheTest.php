<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GroupedProductStaging\Test\Unit\Model\Product\Operation\Update;

use Magento\GroupedProductStaging\Model\Product\Operation\Update\FlushAssociatedProductCache;
use Magento\GroupedProduct\Model\Product\Type\Grouped;

class FlushAssociatedProductCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FlushAssociatedProductCache
     */
    private $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $temporaryUpdateMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $typeInstanceMock;

    protected function setUp()
    {
        $this->typeInstanceMock = $this->getMock(
            \Magento\GroupedProduct\Model\Product\Type\Grouped::class,
            [],
            [],
            '',
            false
        );
        $this->objectMock = $this->getMock(\Magento\Catalog\Model\Product::class, [], [], '', false);
        $this->temporaryUpdateMock = $this->getMock(
            \Magento\CatalogStaging\Model\Product\Operation\Update\TemporaryUpdateProcessor::class,
            [],
            [],
            '',
            false
        );
        $this->model = new FlushAssociatedProductCache();
    }

    public function testBeforeLoadEntity()
    {
        $this->objectMock->expects($this->once())->method('getTypeId')->willReturn(Grouped::TYPE_CODE);
        $this->objectMock->expects($this->once())->method('getTypeInstance')->willReturn($this->typeInstanceMock);
        $this->typeInstanceMock
            ->expects($this->once())
            ->method('flushAssociatedProductsCache')
            ->with($this->objectMock);
        $this->model->beforeLoadEntity($this->temporaryUpdateMock, $this->objectMock);
    }

    public function testBeforeBuildEntity()
    {
        $this->objectMock->expects($this->once())->method('getTypeId')->willReturn('code');
        $this->objectMock->expects($this->never())->method('getTypeInstance');
        $this->model->beforeBuildEntity($this->temporaryUpdateMock, $this->objectMock);
    }
}
