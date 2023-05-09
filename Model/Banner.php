<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Model;

use Magento\Framework\Model\AbstractModel;
use Rbj\BannerSlider\Api\Data\BannerInterface;
use Rbj\BannerSlider\Model\ResourceModel\Banner as BannerResourceModel;

/**
 * Class Banner
 */
class Banner extends AbstractModel implements BannerInterface
{
    /**
     * Banner's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @var int[]
     */
    protected $_stores = [];

    /**
     * @var boolean
     */
    protected $_saveStoresFlag = false;

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(BannerResourceModel::class);
    }

    /**
     * Prepare statuses.
     *
     * @return array
     */
    public function getAvailableStatuses(): array
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return parent::getData(self::BANNER_ID);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return parent::getData(self::TITLE);
    }

    /**
     * Get valid from date
     *
     * @return string|null
     */
    public function getIsActive(): ?string
    {
        return parent::getData(self::IS_ACTIVE);
    }

    /**
     * Get banner Desktop image
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return parent::getData(self::BANNER_IMAGE);
    }

    /**
     * Get banner Mobile image
     *
     * @return string|null
     */
    public function getMobileImage(): ?string
    {
        return parent::getData(self::BANNER_MOBILE_IMAGE);
    }

    /**
     * Get Content Url
     *
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
        return parent::getData(self::CONTENT_URL);
    }

    /**
     * Get Content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return parent::getData(self::CONTENT);
    }

    /**
     * Get Position
     *
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return parent::getData(self::POSITION);
    }

    /**
     * Set ID
     *
     * @param int $bannerId
     * @return BannerInterface
     */
    public function setEntityId($bannerId): BannerInterface
    {
        return $this->setData(self::BANNER_ID, $bannerId);
    }

    /**
     * Set title
     *
     * @param string $bannerTitle
     * @return BannerInterface
     */
    public function setTitle(string $bannerTitle): BannerInterface
    {
        return $this->setData(self::TITLE, $bannerTitle);
    }

    /**
     * Set Slide Status
     *
     * @param string $isActive
     * @return BannerInterface
     */
    public function setIsActive(string $isActive): BannerInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Set banner image
     *
     * @param string $bannerimg
     * @return BannerInterface
     */
    public function setImage(string $bannerimage): BannerInterface
    {
        return $this->setData(self::BANNER_IMAGE, $bannerimage);
    }

    /**
     * Set banner Mobile image
     *
     * @param string $bannerimg
     * @return BannerInterface
     */
    public function setMobileImage(string $mobileImage): BannerInterface
    {
        return $this->setData(self::BANNER_MOBILE_IMAGE, $mobileImage);
    }

    /**
     * Set Content URL
     *
     * @param string $content
     * @return BannerInterface
     */
    public function setContentUrl(string $url): BannerInterface
    {
        return $this->setData(self::CONTENT_URL, $url);
    }

    /**
     * Set Position
     *
     * @param int $position
     * @return BannerInterface
     */
    public function setPosition($position): BannerInterface
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Set Content
     *
     * @param string $content
     * @return BannerInterface
     */
    public function setContent(string $content): BannerInterface
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set store flag
     *
     * @param boolean $value
     * @return $this
     */
    public function setSaveStoresFlag(bool $value): BannerInterface
    {
        $this->_saveStoresFlag = (bool)$value;
        return $this;
    }

    /**
     * Receive store flag
     *
     * @return boolean
     */
    public function getSaveStoresFlag(): bool
    {
        return $this->_saveStoresFlag;
    }

    /**
     * Set store ids
     *
     * @return $this
     */
    public function setStores(array $storesIds): BannerInterface
    {
        $this->setSaveStoresFlag(true);
        $this->_stores = $storesIds;
        return $this;
    }

    /**
     * Receive store ids
     *
     * @return int[]
     */
    public function getStores(): array
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }
}
