<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rbj\BannerSlider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Slider Banner search results.
 */
interface BannerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Banner list.
     *
     * @return \Rbj\BannerSlider\Api\Data\BannerInterface[]
     */
    public function getItems(): array;

    /**
     * Set Banners list.
     *
     * @param \Rbj\BannerSlider\Api\Data\BannerInterface[] $items
     * @return $this
     */
    public function setItems(array $items): BannerSearchResultsInterface;
}
