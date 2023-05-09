<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Ui\Component\Listing\Column\Banner;

use Magento\Store\Ui\Component\Listing\Column\Store\Options as StoreOptions;

/**
 * Store Options for Banners
 */
class Options extends StoreOptions
{
    /**
     * All Store Views value constant
     */
    public const ALL_STORE_VIEWS = '0';

    /**
     * Get All stores options
     *
     * @return string[]
     */
    public function toOptionArray(): array
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->currentOptions['All Store Views']['label'] = __('All Store Views');
        $this->currentOptions['All Store Views']['value'] = self::ALL_STORE_VIEWS;

        $this->generateCurrentOptions();

        $this->options = array_values($this->currentOptions);

        return $this->options;
    }
}
