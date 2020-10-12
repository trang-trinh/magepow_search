<?php
namespace Magepow\Search\Block\Index;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magepow\Search\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $helper;

    protected $collection;

    protected $_productAttributeRepository;

    protected $productCollectionFactory;

    protected $productRepository;

    protected $eavAttribute;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Eav\Api\AttributeRepositoryInterface $eavAttribute,
        Data $helper,
        Collection $collection,
        array $data = []
    ) {
        $this->eavAttribute = $eavAttribute;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->collection = $collection;
        $this->_productAttributeRepository = $productAttributeRepository;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }
    public function getAttributeValue($attribute)
    {
        $value = $this->getRequest()->getQuery($attribute->getAttributeCode());
        return $value;
    }
    public function getAttributeLabel($attribute)
    {
        return $attribute->getStoreLabel();
    }
    public function getSearchPostUrl()
    {
        return $this->getUrl('magepow_search/index/result', ['_secure' => true]);
    }
    public function getAttributeOption($attributeCode)
    {
        $attributeOption = $this->_productAttributeRepository->get($attributeCode)->getOptions();
        return $attributeOption;
    }
    public function getProduct()
    {
        $collection = $this->collection->addAttributeToFilter('brand',212)
            ->addAttributeToFilter('year',array('notnull' => true))
            ->addAttributeToSelect(['brand','year']);
        return $collection;
    }
    public function getAttributeCode()
    {
        $attributeSearch = $this->helper->getConfigModule('source_option/search_attribute');
        $attributeSearch = $this->helper->getConfigArraySerialized($attributeSearch);
        foreach ($attributeSearch as $value)
        {
            $attributeCode = $value['attribute'];
            return $attributeCode;
        }
    }
    public function Attribute($attributeCode){
        $manufacturerAttr = $this->eavAttribute->get(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $allOptions = $manufacturerAttr->getSource()->getAllOptions(false);
        return $allOptions;
    }
    public function getProductById($id)
    {
        return $this->productRepository->getById($id);
    }

    public function getProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }
}
