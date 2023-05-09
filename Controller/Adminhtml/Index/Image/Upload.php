<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Controller\Adminhtml\Index\Image;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Rbj\BannerSlider\Model\Banner\Image\Uploader;

/**
 * Upload Image Controller
 */
class Upload extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Rbj_BannerSlider::banners';


    public function __construct(
        Context $context,
        private Uploader $imageUploader
    ) {
        parent::__construct($context);
    }

    /**
     * Upload image execution action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('image');
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
