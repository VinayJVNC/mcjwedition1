<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Solr\Test\Unit\SearchAdapter;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Search\RequestInterface;
use Magento\Solr\SearchAdapter\Adapter;
use Magento\Solr\SearchAdapter\Aggregation;
use Magento\Solr\SearchAdapter\ConnectionManager;
use Magento\Solr\SearchAdapter\Mapper;
use Magento\Solr\SearchAdapter\ResponseFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConnectionManager|MockObject
     */
    protected $connectionManager;

    /**
     * @var Mapper|MockObject
     */
    private $mapper;

    /**
     * @var ResponseFactory|MockObject
     */
    private $responseFactory;

    /**
     * @var Aggregation\Builder
     */
    private $aggregationBuilder;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Aggregation\StatisticsBuilder
     */
    private $statisticsBuilder;

    protected function setUp()
    {
        $helper = new ObjectManager($this);

        $this->connectionManager = $this->getMockBuilder('Magento\Solr\SearchAdapter\ConnectionManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapper = $this->getMockBuilder('Magento\Solr\SearchAdapter\Mapper')
            ->setMethods(['buildQuery'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseFactory = $this->getMockBuilder('Magento\Solr\SearchAdapter\ResponseFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->aggregationBuilder = $this->getMockBuilder('Magento\Solr\SearchAdapter\Aggregation\Builder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = $this->getMockBuilder('Magento\Framework\Search\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->statisticsBuilder = $this->getMockBuilder('Magento\Solr\SearchAdapter\Aggregation\StatisticsBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapter = $helper->getObject(
            'Magento\Solr\SearchAdapter\Adapter',
            [
                'connectionManager' => $this->connectionManager,
                'mapper' => $this->mapper,
                'responseFactory' => $this->responseFactory,
                'aggregationBuilder' => $this->aggregationBuilder,
                'statisticsBuilder' => $this->statisticsBuilder,
            ]
        );
    }

    public function testQuery()
    {
        $solariumClient = $this->getMockBuilder('Magento\Solr\Model\Client\Solarium')
            ->setMethods(['query'])
            ->disableOriginalConstructor()
            ->getMock();
        $query = $this->getMockBuilder('Solarium\QueryType\Select\Query\Query')
            ->disableOriginalConstructor()
            ->getMock();
        $rawResponse = $this->getMockBuilder('Solarium\Core\Query\Result\ResultInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $queryResponse = $this->getMockBuilder('Magento\Framework\Search\Response\QueryResponse')
            ->disableOriginalConstructor()
            ->getMock();

        $this->connectionManager->expects($this->once())->method('getConnection')->willReturn($solariumClient);
        $this->mapper->expects($this->once())->method('buildQuery')->with($this->request)->willReturn($query);
        $this->statisticsBuilder->expects($this->once())->method('build')->with($this->request, $query);
        $solariumClient->expects($this->once())->method('query')->with($query)->willReturn($rawResponse);
        $this->responseFactory->expects($this->once())
            ->method('create')
            ->with(['documents' => $rawResponse, 'aggregations' => null])
            ->willReturn($queryResponse);

        $this->adapter->query($this->request);
    }
}
