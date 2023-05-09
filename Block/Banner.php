<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Block;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Rbj\BannerSlider\Model\ResourceModel\Banner\Collection;
use Rbj\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;
use Zend_Db_Expr;

/**
 * Banner Block
 */
class Banner extends Template
{
    public function __construct(
		Context $context,
        private CollectionFactory $itemCollectionFactory,
        private DirectoryList $directoryList,
        array $data = []
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }

    /**
     * Retrieve image URL
     *
     * @param string|null $image
     * @param string $path
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl(?string $image, string $path): string
    {
        $url = false;
        if ($image) {
            $url = $this->_storeManager->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ) . $path . '/' . $image;
        }
        return $url;
    }

    /**
     * get image size
     *
     * @param string $image
     * @param string $path
     * @return array
     * @throws FileSystemException
     */
    public function getImageSize(string $image, string $path): array
    {
        $filePath = $this->directoryList->getPath('pub');
        $filePath = $filePath . '/media/' . $path . "/" . $image;

        if(file_exists($filePath))
            return getimagesize($filePath);

        return [];
    }

    /**
     * Item Collection
     *
     * @return Collection
     */
    public function getItemCollection(): Collection
    {
        $collection = $this->itemCollectionFactory->create();
        $collection->addFieldToFilter('is_active', \Rbj\BannerSlider\Model\Banner::STATUS_ENABLED);

        $collection->getSelect()->order(new Zend_Db_Expr("CASE WHEN `position` = '0' THEN 9999 ELSE `position` END"));
        return $collection;
	}

    /**
     * get Configuration Value
     *
     * @return boolean
     */
    public function isAllowed(): bool
    {
        return (bool)$this->scopeConfig->getValue('banners/general/enabled', ScopeInterface::SCOPE_STORE);
    }
}
