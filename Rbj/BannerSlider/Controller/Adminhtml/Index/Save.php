<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Rbj\BannerSlider\Model\Banner;
use Rbj\BannerSlider\Model\BannerFactory;

/**
 * Save Controller
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Rbj_BannerSlider::banners';

    public function __construct(
        Action\Context $context,
        private DataPersistorInterface $dataPersistor,
        private BannerFactory $bannerFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Banner::STATUS_ENABLED;
            }

            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            if (isset($data['image']['0'])) {
                $data['image'] = $data['image']['0']['name'];
            }
            else {
                $data['image'] = null;
            }

            if (isset($data['mobile_image']['0'])) {
                $data['mobile_image'] = $data['mobile_image']['0']['name'];
            }
            else {
                $data['mobile_image'] = null;
            }

            /** @var Banner $model */
            $model = $this->bannerFactory->create();
            $id = $this->getRequest()->getParam('entity_id');

            if ($id) {
                $model->load($id);
            }

            $model->setData($data);
            $model->setStores($this->getRequest()->getParam('store_id', []));

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the banner.'));
                $this->dataPersistor->clear('rbj_banners');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->dataPersistor->set('rbj_banners', $data);

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
