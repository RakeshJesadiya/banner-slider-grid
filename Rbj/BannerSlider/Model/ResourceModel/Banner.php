<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Model\ResourceModel;

use Exception;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Rbj\BannerSlider\BannerImageUploader;
use Rbj\BannerSlider\BannerMobileImageUploader;
use Rbj\BannerSlider\Model\Banner as ModelBanner;

class Banner extends AbstractDb
{
    public const BANNER_STORE_TABLE = 'rbj_banner_store';

    /**
     * Image uploader
     *
     * @var BannerImageUploader
     */
    private $imageUploader;

    /**
     * Image uploader
     *
     * @var BannerMobileImageUploader
     */
    private $mobileImageUploader;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('rbj_banner', 'entity_id');
    }

    /**
     * set Stores
     *
     * @param ModelBanner $banner
     * @return $this
     */
    public function setStores(ModelBanner $banner)
    {
        $connection = $this->getConnection();
        $connection->delete($this->getTable(self::BANNER_STORE_TABLE), ['entity_id = ?' => $banner->getId()]);

        $stores = $banner->getStores();

        if (!is_array($stores)) {
            $stores = [];
        }

        foreach ($stores as $storeId) {
            $data = [];
            $data['store_id'] = $storeId;
            $data['entity_id'] = $banner->getId();
            $connection->insert($this->getTable(self::BANNER_STORE_TABLE), $data);
        }

        return $this;
    }

    /**
     * get Stores
     *
     * @param ModelBanner $banner
     * @return int[]
     */
    public function getStores(ModelBanner $banner)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable(self::BANNER_STORE_TABLE),
            'store_id'
        )->where(
            'entity_id = :entity_id'
        );

        if (!($result = $connection->fetchCol($select, ['entity_id' => $banner->getId()]))) {
            $result = [];
        }

        return $result;
    }

    /**
     * Process banner data before saving
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        if ($object->getSaveStoresFlag()) {
            $this->setStores($object);
        }

        $this->moveImage($object);

        return parent::_afterSave($object);
    }

    /**
     * Process banner data before deleting
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(AbstractModel $object)
    {
        $condition = ['entity_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable(self::BANNER_STORE_TABLE), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Get image uploader
     *
     * @return BannerImageUploader
     *
     * @deprecated
     */
    private function getImageUploader()
    {
        if ($this->imageUploader === null) {
            $this->imageUploader = ObjectManager::getInstance()->get(
                'Rbj\BannerSlider\BannerImageUploader'
            );
        }
        return $this->imageUploader;
    }

    /**
     * Get image uploader
     *
     * @return BannerMobileImageUploader
     *
     * @deprecated
     */
    private function getMobileImageUploader()
    {
        if ($this->mobileImageUploader === null) {
            $this->mobileImageUploader = ObjectManager::getInstance()->get(
                'Rbj\BannerSlider\BannerMobileImageUploader'
            );
        }

        return $this->mobileImageUploader;
    }

    /**
     * Save uploaded file
     *
     * @param DataObject $object
     * @return $this
     */
    private function moveImage($object)
    {
        $image = $object->getData('image', null);

        $mobileImage = $object->getData('mobile_image', null);

        if ($image !== null) {
            try {
                $this->getImageUploader()->moveFileFromTmp($image);
            } catch (Exception $e) {
                // $this->_logger->critical($e);
            }
        }

        if ($mobileImage !== null) {
            try {
                $this->getMobileImageUploader()->moveFileFromTmp($mobileImage);
            } catch (Exception $e) {
                // $this->_logger->critical($e);
            }
        }
        return $this;
    }
}
