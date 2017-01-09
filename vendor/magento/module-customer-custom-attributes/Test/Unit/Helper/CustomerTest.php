<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerCustomAttributes\Test\Unit\Helper;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_dataHelperMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_inputValidatorMock;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(
            'Magento\Framework\App\Helper\Context'
        )->disableOriginalConstructor()->getMock();

        $this->_dataHelperMock = $this->getMockBuilder(
            'Magento\CustomerCustomAttributes\Helper\Data'
        )->disableOriginalConstructor()->getMock();
        $this->_dataHelperMock->expects(
            $this->any()
        )->method(
            'getAttributeInputTypes'
        )->will(
            $this->returnValue([])
        );

        $this->_inputValidatorMock = $this->getMockBuilder(
            'Magento\Eav\Model\Adminhtml\System\Config\Source\Inputtype\Validator'
        )->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     * @param array $data
     * @param bool $validatorResult
     * @dataProvider getFilterExceptionDataProvider
     */
    public function filterPostDataExceptionTest($data, $validatorResult)
    {
        $this->_inputValidatorMock->expects(
            $this->any()
        )->method(
            'isValid'
        )->will(
            $this->returnValue($validatorResult)
        );

        $this->_inputValidatorMock->expects(
            $this->any()
        )->method(
            'getMessages'
        )->will(
            $this->returnValue(['Some error message'])
        );

        $helper = new \Magento\CustomerCustomAttributes\Helper\Customer(
            $this->_contextMock,
            $this->getMock('Magento\Eav\Model\Config', [], [], '', false),
            $this->getMockForAbstractClass('Magento\Framework\Stdlib\DateTime\TimezoneInterface'),
            $this->getMock('Magento\Framework\Filter\FilterManager', [], [], '', false),
            $this->_dataHelperMock,
            $this->_inputValidatorMock
        );

        $this->setExpectedException('Magento\Framework\Exception\LocalizedException');
        $helper->filterPostData($data);
    }

    /**
     *
     * @param array $data
     * @param array $expectedResultData
     * @dataProvider getFilterDataProvider
     * @test
     */
    public function filterPostDataTest($data, $expectedResultData)
    {
        $this->_inputValidatorMock->expects($this->once())->method('isValid')->will($this->returnValue(true));
        $this->_inputValidatorMock->expects($this->never())->method('getMessages');

        $helper = new \Magento\CustomerCustomAttributes\Helper\Customer(
            $this->_contextMock,
            $this->getMock('Magento\Eav\Model\Config', [], [], '', false),
            $this->getMockForAbstractClass('Magento\Framework\Stdlib\DateTime\TimezoneInterface'),
            $this->getMock('Magento\Framework\Filter\FilterManager', [], [], '', false),
            $this->_dataHelperMock,
            $this->_inputValidatorMock
        );

        $dataResult = $helper->filterPostData($data);

        $this->assertEquals($dataResult, $expectedResultData);
    }

    /**
     * Test exception data provider
     *
     * @return array
     */
    public function getFilterExceptionDataProvider()
    {
        return [
            [
                ['frontend_label' => [], 'frontend_input' => 'file', 'attribute_code' => 'correct_code'],
                false,
            ],
            [
                ['frontend_label' => [], 'frontend_input' => 'select', 'attribute_code' => 'inCorrect_code'],
                true
            ],
            [
                [
                    'frontend_label' => [],
                    'frontend_input' => 'select',
                    'attribute_code' => 'in!correct_code',
                ],
                true
            ]
        ];
    }

    /**
     * Test filter data provider
     *
     * @return array
     */
    public function getFilterDataProvider()
    {
        return [
            [
                [
                    'frontend_label' => ['<script></script>'],
                    'frontend_input' => 'file',
                    'attribute_code' => 'correct_code',
                ],
                ['frontend_label' => [''], 'frontend_input' => 'file', 'attribute_code' => 'correct_code'],
            ]
        ];
    }
}
