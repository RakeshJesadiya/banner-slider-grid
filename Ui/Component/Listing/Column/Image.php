<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Ui\Component\Listing\Column;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Image and Mobile Image Column listing
 */
class Image extends Column
{
    public const NAME = 'image';
    private const MOBILE_IMAGE = 'mobile_image';
    private const ALT_FIELD  = 'title';

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private UrlInterface $urlBuilder,
        private StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if(isset($dataSource['data']['items'])) {

            foreach($dataSource['data']['items'] as &$item) {
                if(isset($item[self::NAME]) && trim($item[self::NAME]) != '') {
                    $url = $this->storeManager->getStore()->getBaseUrl(
                        UrlInterface::URL_TYPE_MEDIA
                    ) . 'banners/image/' . $item[self::NAME];

                    $item[self::NAME . '_src'] = $url;
                    $item[self::NAME . '_alt'] = isset($item[self::ALT_FIELD]) ? $item[self::ALT_FIELD]: '';
                    $item[self::NAME . '_link'] = $this->urlBuilder->getUrl(
                        'banners/index/edit',
                        ['entity_id' => $item['entity_id']]
                    );
                    $item[self::NAME . '_orig_src'] = $url;
                }

                if(isset($item[self::MOBILE_IMAGE]) && trim($item[self::MOBILE_IMAGE]) != '') {
                    $url = $this->storeManager->getStore()->getBaseUrl(
                        UrlInterface::URL_TYPE_MEDIA
                    ) . 'banners/mobile/image/' . $item[self::MOBILE_IMAGE];

                    $item[self::MOBILE_IMAGE . '_src'] = $url;
                    $item[self::MOBILE_IMAGE . '_alt'] = isset($item[self::ALT_FIELD]) ? $item[self::ALT_FIELD]: '';
                    $item[self::MOBILE_IMAGE . '_link'] = $this->urlBuilder->getUrl(
                        'banners/index/edit',
                        ['entity_id' => $item['entity_id']]
                    );
                    $item[self::MOBILE_IMAGE . '_orig_src'] = $url;
                }
            }
        }

        return $dataSource;
    }
}
