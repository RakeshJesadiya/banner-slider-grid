<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Model\Banner\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Rbj\BannerSlider\Model\Banner;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    public function __construct(private Banner $bannerModel)
    {
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $availableOptions = $this->bannerModel->getAvailableStatuses();
        $options = [];

        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $options;
    }
}
