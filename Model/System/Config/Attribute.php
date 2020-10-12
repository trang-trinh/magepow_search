<?php
namespace Magepow\Search\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class Attribute implements ArrayInterface
{
    protected $_collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
    ) {
        $this->_collectionFactory = $collectionFactory;
    }
    public function toOptionArray()
    {
        $option = ['' => __('Select a attribute')];
        $collection = $this->_collectionFactory->create()
            ->addVisibleFilter();
        foreach ($collection as $item) {
            $option[$item->getAttributeCode()] = $item->getFrontendLabel();
        }
        return $option;
    }
}
