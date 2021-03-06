<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Staging\Ui\Component\Listing\Column\Entity;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Staging\Model\Preview\UrlBuilder;
use Magento\Staging\Model\VersionHistoryInterface;
use Magento\Staging\Model\VersionManager;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class UpdateActions
 */
class UpdateActions extends Column
{
    /**
     * @var UrlBuilder
     */
    protected $previewUrlBuilder;

    /**
     * @var string
     */
    protected $entityIdentifier;

    /**
     * @var string
     */
    protected $entityColumn;

    /**
     * @var string
     */
    protected $jsModalProvider;

    /**
     * @var string
     */
    protected $jsLoaderProvider;

    /**
     * @var VersionHistoryInterface
     */
    private $history;

    /**
     * UpdateActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param VersionHistoryInterface $history
     * @param string $entityIdentifier
     * @param string $entityColumn
     * @param string $jsModalProvider
     * @param string $jsLoaderProvider
     * @param array $components
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        VersionHistoryInterface $history,
        $entityIdentifier,
        $entityColumn,
        $jsModalProvider,
        $jsLoaderProvider,
        array $components = [],
        array $data = []
    ) {
        $this->entityIdentifier = $entityIdentifier;
        $this->entityColumn = $entityColumn;
        $this->jsModalProvider = $jsModalProvider;
        $this->jsLoaderProvider = $jsLoaderProvider;
        $this->history = $history;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Returns actions list for upcoming campaign
     *
     * @param array $item
     * @return array
     */
    private function getUpcomingAction($item)
    {
        return [
            'edit' => [
                'callback' => [
                    [
                        'provider' => $this->jsLoaderProvider,
                        'target' => 'destroyInserted',
                    ],
                    [
                        'provider' => $this->jsLoaderProvider,
                        'target' => 'updateData',
                        'params' => [
                            $this->entityIdentifier => $item[$this->entityColumn],
                            'update_id' => $item['created_in'],
                        ],
                    ],
                    [
                        'provider' => $this->jsModalProvider,
                        'target' => 'openModal',
                    ],
                ],
                'label' => __('View/Edit'),
                'href' => '#'
            ],
        ];
    }

    /**
     * Returns dummy action for currently active campaign
     *
     * @return array
     */
    private function getCurrentPlaceholder()
    {
        return [
            'edit' => [
                'callback' => [],
                'label' => __('This action is unavailable'),
                'href' => '#'
            ],
        ];
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['created_in']) && $item['created_in'] > $this->history->getCurrentId()) {
                    $item[$this->getData('name')] = $this->getUpcomingAction($item);
                } else {
                    $item[$this->getData('name')] = $this->getCurrentPlaceholder($item);
                }
            }
        }
        return $dataSource;
    }
}
