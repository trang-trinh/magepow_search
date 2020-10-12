<?php
namespace Magepow\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Result extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface, HttpPostActionInterface
{
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
