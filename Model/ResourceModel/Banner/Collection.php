<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Model\ResourceModel\Banner;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Rbj\BannerSlider\Model\ResourceModel\Banner;

/**
 * BannerSlider Resource Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        private StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Rbj\BannerSlider\Model\Banner::class, Banner::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }

    /**
     * Load Banner store data after loading
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad(Banner::BANNER_STORE_TABLE, 'entity_id');
        return parent::_afterLoad();
    }

    /**
     * Perform Loading Banner data with primary key
     *
     * @param string $tableName
     * @param string $linkField
     * @return void
     * @throws NoSuchEntityException
     */
    protected function performAfterLoad(string $tableName, string $linkField): void
    {
        $linkedIds = $this->getColumnValues($linkField);

        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['rbj_banner_store' => $this->getTable($tableName)])
                ->where('rbj_banner_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);

            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);

                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }

                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);

                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }

                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storesData[$linkedId]);
                }
            }
        }
    }

    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null): Collection
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter(mixed $store, bool $withAdmin = true): Collection
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore(): void
    {
        $this->joinStoreRelationTable(Banner::BANNER_STORE_TABLE, 'entity_id');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @param string $tableName
     * @param string $linkField
     * @return void
     */
    protected function joinStoreRelationTable(string $tableName, string $linkField): void
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = store_table.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
        parent::_renderFiltersBefore();
    }
}
