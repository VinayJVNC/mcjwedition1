<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Test\Unit\Controller\Adminhtml\Rma;

use Magento\Rma\Test\Unit\Controller\Adminhtml\RmaTest;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * @covers \Magento\Rma\Controller\Adminhtml\Rma\Viewfile
 */
class ViewfileTest extends RmaTest
{
    /**
     * @var string
     */
    protected $name = 'Viewfile';

    /**
     * @var \Magento\Framework\Controller\Result\Raw|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRawMock;

    /**
     * @var \Magento\Framework\Filesystem|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileSystemMock;

    /**
     * @var \Magento\Framework\Filesystem\Directory\Read|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $readDirectoryMock;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRawFactoryMock;

    /**
     * @var \Magento\Framework\Url\DecoderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlDecoderMock;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileFactoryMock;

    /**
     * @var \Magento\Framework\App\ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileResponseMock;

    /**
     * @var \Magento\Framework\Filesystem\File\Read|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileReadMock;

    protected function setUp()
    {
        $this->readDirectoryMock = $this->getMockBuilder('Magento\Framework\Filesystem\Directory\Read')
            ->disableOriginalConstructor()
            ->getMock();
        $this->fileSystemMock = $this->getMockBuilder('Magento\Framework\Filesystem')
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultRawFactoryMock = $this->getMockBuilder('Magento\Framework\Controller\Result\RawFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultRawMock = $this->getMockBuilder('Magento\Framework\Controller\Result\Raw')
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlDecoderMock = $this->getMockBuilder('Magento\Framework\Url\DecoderInterface')
            ->getMock();
        $this->fileFactoryMock = $this->getMockBuilder('Magento\Framework\App\Response\Http\FileFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $this->fileResponseMock = $this->getMockBuilder('Magento\Framework\App\ResponseInterface')
            ->getMock();
        $this->fileReadMock = $this->getMockBuilder('Magento\Framework\Filesystem\File\Read')
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileSystemMock->expects($this->any())
            ->method('getDirectoryRead')
            ->with(DirectoryList::MEDIA)
            ->willReturn($this->readDirectoryMock);

        parent::setUp();
    }

    /**
     * @return array
     */
    protected function getConstructArguments()
    {
        $arguments = parent::getConstructArguments();
        $arguments['filesystem'] = $this->fileSystemMock;
        $arguments['resultRawFactory'] = $this->resultRawFactoryMock;
        $arguments['urlDecoder'] = $this->urlDecoderMock;
        $arguments['fileFactory'] = $this->fileFactoryMock;
        return $arguments;
    }

    /**
     * @covers \Magento\Rma\Controller\Adminhtml\Rma\Viewfile::execute
     * @throws \Magento\Framework\Exception\NotFoundException
     * @expectedException \Magento\Framework\Exception\NotFoundException
     */
    public function testExecuteNoParamsShouldThrowException()
    {
        $this->action->execute();
    }

    /**
     * @covers \Magento\Rma\Controller\Adminhtml\Rma\Viewfile::execute
     */
    public function testExecuteGetFile()
    {
        $file = 'file';
        $fileDecoded = 'file_decoded';
        $absolutePath = 'absolute/path';

        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->with('file')
            ->willReturn($file);
        $this->urlDecoderMock->expects($this->any())
            ->method('decode')
            ->with($file)
            ->willReturn($fileDecoded);
        $this->readDirectoryMock->expects($this->any())
            ->method('isExist')
            ->willReturn(true);
        $this->readDirectoryMock->expects($this->any())
            ->method('getAbsolutePath')
            ->willReturn($absolutePath);
        $this->fileFactoryMock->expects($this->once())
            ->method('create')
            ->with(
                $fileDecoded,
                ['type' => 'filename', 'value' => $absolutePath],
                DirectoryList::MEDIA
            )
            ->willReturn($this->fileResponseMock);

        $this->action->execute();
    }

    /**
     * @covers \Magento\Rma\Controller\Adminhtml\Rma\Viewfile::execute
     */
    public function testExecuteGetImage()
    {
        $file = 'file';
        $fileDecoded = 'file_decoded';
        $fileContents = 'file_contents';
        $fileStat = ['size' => 10, 'mtime' => 10];

        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['file', null, null],
                    ['image', null, $file]
                ]
            );
        $this->urlDecoderMock->expects($this->any())
            ->method('decode')
            ->with($file)
            ->willReturn($fileDecoded);
        $this->fileReadMock->expects($this->once())
            ->method('read')
            ->with($fileStat['size'])
            ->willReturn($fileContents);
        $this->readDirectoryMock->expects($this->any())
            ->method('isExist')
            ->willReturn(true);
        $this->readDirectoryMock->expects($this->any())
            ->method('openFile')
            ->willReturn($this->fileReadMock);
        $this->readDirectoryMock->expects($this->any())
            ->method('stat')
            ->with('rma_item/file_decoded')
            ->willReturn($fileStat);
        $this->resultRawMock->expects($this->any())
            ->method('setHttpResponseCode')
            ->with(200)
            ->willReturnSelf();
        $this->resultRawMock->expects($this->any())
            ->method('setHeader')
            ->willReturnSelf();
        $this->resultRawMock->expects($this->once())
            ->method('setContents')
            ->with($fileContents);
        $this->resultRawFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRawMock);

        $this->assertSame($this->resultRawMock, $this->action->execute());
    }
}