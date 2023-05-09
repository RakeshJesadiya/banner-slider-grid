<?php declare(strict_types=1);

/**
 * @author Rakesh Jesadiya <rakesh@rakeshjesadiya.com>
 * @package Rbj_BannerSlider
 */

namespace Rbj\BannerSlider\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Rbj\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;

/**
 * Class MassDelete action
 */
class MassDelete extends Action
{
    public function __construct(
        Context $context,
        private Filter $filter,
        private CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute(): Redirect
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $page) {
            $page->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
