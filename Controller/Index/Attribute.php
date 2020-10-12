<?php
namespace Magepow\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magepow\Search\Helper\Data;

class Attribute extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;

    protected $_productAttributeRepository;

    protected $resultJsonFactory;

    protected $_collection;

    protected $_helper;

    protected $_productCollectionFactory;
    protected $productRepository;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        Collection $collection,
        Data $_helper
    ) {
        $this->productRepository = $productRepository;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_helper = $_helper;
        $this->_productAttributeRepository = $productAttributeRepository;
        $this->_resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_collection = $collection;
        parent::__construct($context);
    }
    public function execute()
    {

        $result = $this->resultJsonFactory->create();
        $collection = $this->_productCollectionFactory->create();
        $html='<option selected="selected" value="">Please Select</option>';
        $attributeCode1 = $this->getRequest()->getParam('attribute_code1');
        $attributeOption1 = $this->getRequest()->getParam('attribute_option1');
        $attributeCode2 = $this->getRequest()->getParam('attribute_code2');

        if($attributeOption1)
        {
            $product = $collection->addAttributeToFilter($attributeCode1,$attributeOption1)
                ->addAttributeToFilter($attributeCode2,array('notnull' => true))
                ->groupByAttribute($attributeCode2)
                ->addAttributeToSelect([$attributeCode1,$attributeCode2]);
            foreach ($product as $p)
            {
                $attributeValue = $p[$attributeCode2];
            }
            $attribute = $this->_productAttributeRepository->get($attributeCode2)->getOptions();
            foreach ($attribute as $a)
            {
                if($a->getValue() === $attributeValue)
                {
                    $html.='<option selected="selected" value="'.$a->getValue().'">'.$a->getLabel().'</option>';
                }
            }
        }
        return $result->setData(['success'=>true,'value'=>$html]);
    }
}

