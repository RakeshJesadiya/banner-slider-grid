<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Model;

use Exception;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Rbj\BannerSlider\Api\BannerRepositoryInterface;
use Rbj\BannerSlider\Api\Data\BannerInterface;
use Rbj\BannerSlider\Api\Data\BannerInterfaceFactory;
use Rbj\BannerSlider\Api\Data\BannerSearchResultsInterfaceFactory;
use Rbj\BannerSlider\Model\ResourceModel\Banner as ResourceBanner;
use Rbj\BannerSlider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Rbj\BannerSlider\Model\ResourceModel\Banner\Collection;

/**
 * Class BannerRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BannerRepository implements BannerRepositoryInterface
{
    public function __construct(
        private ResourceBanner $resource,
        private BannerFactory $bannerFactory,
        private BannerInterfaceFactory $bannerInterfaceFactory,
        private BannerCollectionFactory $bannerCollectionFactory,
        private BannerSearchResultsInterfaceFactory $searchResultsFactory,
        private DataObjectHelper $dataObjectHelper,
        private DataObjectProcessor $dataObjectProcessor,
        private StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Save Banner data
     *
     * @param BannerInterface $banner
     * @return BannerInterface
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function save(BannerInterface $banner): BannerInterface
    {
        $storeId = $this->storeManager->getStore()->getId();
        $banner->setStoreId($storeId);
        try {
            $this->resource->save($banner);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $banner;
    }

    /**
     * Load Banner data by given Banner Identity
     *
     * @param string $bannerId
     * @return BannerInterface
     * @throws NoSuchEntityException
     */
    public function getById($bannerId): BannerInterface
    {
        $banner = $this->bannerFactory->create();
        $this->resource->load($banner, $bannerId);
        if (!$banner->getId()) {
            throw new NoSuchEntityException(__('Slider Banner with id "%1" does not exist.', $bannerId));
        }
        return $banner;
    }

    /**
     * Load Banner data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return Collection
     */
    public function getList(SearchCriteriaInterface $criteria): Collection
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->bannerCollectionFactory->create();

        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();

        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $banners = [];

        /** @var Banner $bannerModel */
        foreach ($collection as $bannerModel) {
            $bannerData = $this->bannerInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $bannerData,
                $bannerModel->getData(),
                BannerInterface::class
            );
            $banners[] = $this->dataObjectProcessor->buildOutputDataArray(
                $bannerData,
                BannerInterface::class
            );
        }
        $searchResults->setItems($banners);

        return $searchResults;
    }

    /**
     * Delete Banner
     *
     * @param BannerInterface $banner
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(BannerInterface $banner): bool
    {
        try {
            $this->resource->delete($banner);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete Banner by given Banner Identity
     *
     * @param int $bannerId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $bannerId): bool
    {
        return $this->delete($this->getById($bannerId));
    }
}
