<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rbj\BannerSlider\Api\Data;

use Rbj\BannerSlider\Block\Banner;

/**
 * Static Banner interface.
 * @api
 */
interface BannerInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public  const BANNER_ID           = 'entity_id';
    public  const TITLE               = 'title';
    public  const IS_ACTIVE           = 'is_active';
    public  const BANNER_IMAGE        = 'image';
    public  const BANNER_MOBILE_IMAGE = 'mobile_image';
    public  const CONTENT_URL         = 'content_url';
    public  const CONTENT             = 'banner_content';
    public  const POSITION            = 'position';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Get valid from date
     *
     * @return string|null
     */
    public function getIsActive(): ?string;

    /**
     * Get banner Desktop image
     *
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * Get banner Mobile image
     *
     * @return string|null
     */
    public function getMobileImage(): ?string;

    /**
     * Get banner content url
     *
     * @return string|null
     */
    public function getContentUrl(): ?string;

    /**
     * Get Content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Get Sort Order
     *
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * Set ID
     *
     * @param int $bannerId
     * @return BannerInterface
     */
    public function setEntityId($bannerId): BannerInterface;

    /**
     * Set title
     *
     * @param string $bannerTitle
     * @return BannerInterface
     */
    public function setTitle(string $bannerTitle): BannerInterface;

    /**
     * Set Slide Status
     *
     * @param string $isActive
     * @return BannerInterface
     */
    public function setIsActive(string $isActive): BannerInterface;

    /**
     * Set Content URL
     *
     * @param string $content
     * @return BannerInterface
     */
    public function setContentUrl(string $url): BannerInterface;

    /**
     * Set Content
     *
     * @param string $content
     * @return BannerInterface
     */
    public function setContent(string $content): BannerInterface;

    /**
     * Set banner image
     *
     * @param string $bannerimg
     * @return BannerInterface
     */
    public function setImage(string $bannerimage): BannerInterface;

    /**
     * Set banner Mobile image
     *
     * @param string $bannerimg
     * @return BannerInterface
     */
    public function setMobileImage(string $mobileImage): BannerInterface;

    /**
     * Set Position
     *
     * @param int $position
     * @return BannerInterface
     */
    public function setPosition($position): BannerInterface;
}
