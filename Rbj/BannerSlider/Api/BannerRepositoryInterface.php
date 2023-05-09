<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rbj\BannerSlider\Api\Data\BannerInterface;
use Rbj\BannerSlider\Api\Data\BannerSearchResultsInterface;

/**
 * CMS block CRUD interface.
 */
interface BannerRepositoryInterface
{
    /**
     * Save block.
     *
     * @param BannerInterface $banner
     * @return BannerInterface
     * @throws LocalizedException
     */
    public function save(BannerInterface $banner);

    /**
     * Retrieve block.
     *
     * @param int $blockId
     * @return BannerInterface
     * @throws LocalizedException
     */
    public function getById(int $bannerId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return BannerSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param BannerInterface $banner
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(BannerInterface $banner);

    /**
     * Delete banner by ID.
     *
     * @param int $bannerId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $bannerId);
}
