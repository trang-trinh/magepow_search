<?php
namespace Magepow\Search\Controller\Adminhtml\Search;

use Magento\Framework\Controller\ResultFactory;

class Option extends \Magento\Backend\App\Action
{
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
