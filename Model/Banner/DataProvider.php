<?php

namespace Rbj\BannerSlider\Model\Banner;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rbj\BannerSlider\Model\Banner;
use Rbj\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    private ?array $loadedData = null;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        private CollectionFactory $bannerCollectionFactory,
        private DataPersistorInterface $dataPersistor,
        private StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $bannerCollectionFactory->create();
    }

    /**
     * Get data
     *
     * @return array
     * @throws LocalizedException
     */
    public function getData(): array
    {
        if (null !== $this->loadedData) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /** @var $banner Banner */
        foreach ($items as $banner) {
            /* get image url */
            $bannerData = $banner->getData();
            if (isset($bannerData['image'])) {
                unset($bannerData['image']);
                $bannerData['image'][0]['name'] = $banner->getData('image');
                $bannerData['image'][0]['url'] = $this->getImageUrl($banner->getData('image'));
            }

            if (isset($bannerData['mobile_image'])) {
                unset($bannerData['mobile_image']);
                $bannerData['mobile_image'][0]['name'] = $banner->getData('mobile_image');
                $bannerData['mobile_image'][0]['url'] = $this->getMobileImageUrl($banner->getData('mobile_image'));
            }

            $this->loadedData[$banner->getId()] = $bannerData;
        }

        $data = $this->dataPersistor->get('rbj_banners');
        if (!empty($data)) {
            $banner = $this->collection->getNewEmptyItem();
            $banner->setData($data);
            $this->loadedData[$banner->getId()] = $banner->getData();
            $this->dataPersistor->clear('rbj_banners');
        }

        if (null === $this->loadedData) {
            $this->loadedData = [];
        }

        return $this->loadedData;
    }

    /**
     * Retrieve image URL
     *
     * @param string|null $image
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getImageUrl(?string $image): string
    {
        $url = '';
        if ($image) {
            $url = $this->storeManager->getStore()->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . 'banners/image/' . $image;
        }

        return $url;
    }

    /**
     * Retrieve image URL
     *
     * @param string|null $image
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getMobileImageUrl(?string $image): string
    {
        $url = '';
        if ($image) {
            $url = $this->storeManager->getStore()->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . 'banners/mobile/image/' . $image;
        }

        return $url;
    }
}
