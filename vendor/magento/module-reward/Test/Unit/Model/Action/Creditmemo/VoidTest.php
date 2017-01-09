<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reward\Test\Unit\Model\Action\Creditmemo;

class VoidTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Reward\Model\Action\Creditmemo\Void
     */
    protected $model;

    protected function setUp()
    {
        $this->model = new \Magento\Reward\Model\Action\Creditmemo\Void();
    }

    /**
     * @param array $args
     * @param string $expectedResult
     *
     * @dataProvider getHistoryMessageDataProvider
     * @covers \Magento\Reward\Model\Action\Creditmemo\Void::getHistoryMessage
     */
    public function testGetHistoryMessage(array $args, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->model->getHistoryMessage($args));
    }

    /**
     * @return array
     */
    public function getHistoryMessageDataProvider()
    {
        return [
            [
                'args' => [],
                'expectedResult' => 'Points voided at order # refund.',
            ],
            [
                'args' => ['increment_id' => 1],
                'expectedResult' => 'Points voided at order #1 refund.'
            ]
        ];
    }
}
