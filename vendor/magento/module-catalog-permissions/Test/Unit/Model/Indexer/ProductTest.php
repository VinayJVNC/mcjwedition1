<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogPermissions\Test\Unit\Model\Indexer;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\CatalogPermissions\Model\Indexer\Product
     */
    protected $model;

    /**
     * @var Product\Action\FullFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fullMock;

    /**
     * @var Product\Action\RowsFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rowsMock;

    /**
     * @var \Magento\Framework\Indexer\IndexerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $indexerMock;

    /**
     * @var \Magento\Framework\Indexer\IndexerRegistry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $indexerRegistryMock;

    /**
     * @var \Magento\Framework\Indexer\CacheContext|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $cacheContextMock;

    protected function setUp()
    {
        $this->fullMock = $this->getMock(
            'Magento\CatalogPermissions\Model\Indexer\Category\Action\FullFactory',
            ['create'],
            [],
            '',
            false
        );

        $this->rowsMock = $this->getMock(
            'Magento\CatalogPermissions\Model\Indexer\Product\Action\RowsFactory',
            ['create'],
            [],
            '',
            false
        );

        $methods = ['getId', 'load', 'isInvalid', 'isWorking', '__wakeup'];
        $this->indexerMock = $this->getMockForAbstractClass(
            'Magento\Framework\Indexer\IndexerInterface',
            [],
            '',
            false,
            false,
            true,
            $methods
        );

        $this->indexerRegistryMock = $this->getMock(
            'Magento\Framework\Indexer\IndexerRegistry',
            ['get'],
            [],
            '',
            false
        );

        $this->model = new \Magento\CatalogPermissions\Model\Indexer\Product(
            $this->fullMock,
            $this->rowsMock,
            $this->indexerRegistryMock
        );

        $this->cacheContextMock = $this->getMock(\Magento\Framework\Indexer\CacheContext::class, [], [], '', false);

        $cacheContextProperty = new \ReflectionProperty(
            \Magento\CatalogPermissions\Model\Indexer\Product::class,
            'cacheContext'
        );
        $cacheContextProperty->setAccessible(true);
        $cacheContextProperty->setValue($this->model, $this->cacheContextMock);
    }

    public function testExecuteWithIndexerWorking()
    {
        $ids = [1, 2, 3];

        $this->indexerMock->expects($this->once())->method('isWorking')->will($this->returnValue(true));
        $this->indexerRegistryMock->expects($this->once())
            ->method('get')
            ->with(\Magento\CatalogPermissions\Model\Indexer\Product::INDEXER_ID)
            ->will($this->returnValue($this->indexerMock));

        $rowMock = $this->getMock(
            'Magento\CatalogPermissions\Model\Indexer\Product\Action\Rows',
            ['execute'],
            [],
            '',
            false
        );
        $rowMock->expects($this->at(0))->method('execute')->with($ids, true)->will($this->returnSelf());
        $rowMock->expects($this->at(1))->method('execute')->with($ids, false)->will($this->returnSelf());

        $this->rowsMock->expects($this->once())->method('create')->will($this->returnValue($rowMock));

        $this->cacheContextMock->expects($this->once())
            ->method('registerEntities')
            ->with(\Magento\Catalog\Model\Product::CACHE_TAG, $ids);

        $this->model->execute($ids);
    }

    public function testExecuteWithIndexerNotWorking()
    {
        $ids = [1, 2, 3];

        $this->indexerMock->expects($this->once())->method('isWorking')->will($this->returnValue(false));
        $this->indexerRegistryMock->expects($this->once())
            ->method('get')
            ->with(\Magento\CatalogPermissions\Model\Indexer\Product::INDEXER_ID)
            ->will($this->returnValue($this->indexerMock));

        $rowMock = $this->getMock(
            'Magento\CatalogPermissions\Model\Indexer\Product\Action\Rows',
            ['execute'],
            [],
            '',
            false
        );
        $rowMock->expects($this->once())->method('execute')->with($ids, false)->will($this->returnSelf());

        $this->rowsMock->expects($this->once())->method('create')->will($this->returnValue($rowMock));

        $this->model->execute($ids);
    }

    public function testExecuteFull()
    {
        /** @var \Magento\CatalogPermissions\Model\Indexer\Category\Action\Full $categoryIndexerFlatFull */
        $categoryIndexerFlatFull = $this->getMock(
            \Magento\CatalogPermissions\Model\Indexer\Category\Action\Full::class,
            [],
            [],
            '',
            false
        );
        $this->fullMock->expects($this->once())
            ->method('create')
            ->willReturn($categoryIndexerFlatFull);
        $categoryIndexerFlatFull->expects($this->once())
            ->method('execute');
        $this->cacheContextMock->expects($this->once())
            ->method('registerTags')
            ->with(
                [
                    \Magento\Catalog\Model\Category::CACHE_TAG,
                    \Magento\Catalog\Model\Product::CACHE_TAG
                ]
            );
        $this->model->executeFull();
    }
}
