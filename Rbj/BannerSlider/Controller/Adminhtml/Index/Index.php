<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Index Controller
 */
class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Rbj_BannerSlider::banners';

    public function __construct(
        Context $context,
        private PageFactory $resultPageFactory,
        private DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rbj_BannerSlider::banners');
        $resultPage->addBreadcrumb(__('Banners'), __('Banners'));
        $resultPage->addBreadcrumb(__('Manage Banners'), __('Manage Banners'));
        $resultPage->getConfig()->getTitle()->prepend(__('Banners'));

        $this->dataPersistor->clear('rbj_banners');

        return $resultPage;
    }
}
