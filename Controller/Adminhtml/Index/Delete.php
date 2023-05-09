<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Rbj\BannerSlider\Model\BannerFactory;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Rbj_BannerSlider::banners';

    public function __construct(
        Context $context,
        private BannerFactory $bannerFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // init model and delete
                $model = $this->bannerFactory->create()->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The banner has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a banner to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
