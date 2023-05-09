<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Rbj\BannerSlider\Api\BannerRepositoryInterface as BannerRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Rbj\BannerSlider\Api\Data\BannerInterface;

/**
 * Inline Edit controller
 */
class InlineEdit extends Action
{
    public function __construct(
        Context $context,
        private BannerRepository $bannerRepository,
        private JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $bannerId) {
                    $banner = $this->bannerRepository->getById($bannerId);
                    try {
                        $banner->setData(array_merge($banner->getData(), $postItems[$bannerId]));
                        $this->bannerRepository->save($banner);
                    } catch (Exception $e) {
                        $messages[] = $this->getErrorWithBannerId(
                            $banner,
                            $e->getMessage()
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add banner title to error message
     *
     * @param BannerInterface $banner
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithBannerId(BannerInterface $banner, string $errorText): string
    {
        return '[Banner ID: ' . $banner->getId() . '] ' . $errorText;
    }
}
