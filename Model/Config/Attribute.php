<?php
namespace Magepow\Search\Model\Config;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\CatalogSearch\Model\Advanced;
use Magento\Store\Model\StoreManagerInterface;

class Attribute
{
    protected $_attributeCollectionFactory;

    protected $_productFactory;

    protected $_entityAttributeCollectionFactory;

    protected $_productAttributeCollectionFactory;

    protected $_catalogSearchAdvanced;

    protected $_productAttributeRepository;

    protected $_eavAttribute;

    protected $_attribute;

    protected $_isScopePrivate;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $entitycollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $productcollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        Advanced $catalogSearchAdvanced,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
        AttributeCollectionFactory $attributeCollectionFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_attribute = $attribute;
        $this->_eavAttribute = $eavAttribute;
        $this->_productAttributeRepository = $productAttributeRepository;
        $this->_catalogSearchAdvanced = $catalogSearchAdvanced;
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_productFactory = $productFactory;
        $this->_entityAttributeCollectionFactory = $entitycollectionFactory;
        $this->_productAttributeCollectionFactory = $productcollectionFactory;
        $this->_isScopePrivate = true;
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

    public function getSearchableAttributes()
    {
        $attributes = $this->_catalogSearchAdvanced->getAttributes();
        return $attributes;
    }
}
