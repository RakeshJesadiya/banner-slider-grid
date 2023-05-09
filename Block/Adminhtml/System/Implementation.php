<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Block\Adminhtml\System;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Implementation extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $html = '<div class="notices-wrapper">
                    <div class="message">
                        <strong>'.__('Copy code from below to the CMS page or block.').'</strong><br/>
                        {{block class="Rbj\BannerSlider\Block\Banner" name="slider_display" template="Rbj_BannerSlider::bannerslider.phtml"}}
                    </div>
                    <div class="message">
                        <strong>'.__('Copy code from below to the layout XML file.').'</strong><br/>
                        &lt;block class="Rbj\BannerSlider\Block\Banner" name="slider_display" template="Rbj_BannerSlider::bannerslider.phtml" &lt;/block>
                    </div>
                </div>';

        return $html;
    }
}
