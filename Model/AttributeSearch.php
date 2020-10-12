<?php
namespace Magepow\Search\Model;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogSearch\Model\Advanced\ProductCollectionPrepareStrategyProvider;
use Magento\CatalogSearch\Model\ResourceModel\AdvancedFactory;
use Magento\CatalogSearch\Model\Search\ItemCollectionProviderInterface;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class AttributeSearch extends \Magento\Framework\Model\AbstractModel
{
    protected $_searchCriterias = [];

    protected $_productCollection;

    protected $_catalogConfig;

    protected $_catalogProductVisibility;

    protected $_attributeCollectionFactory;

    protected $_storeManager;

    protected $_productFactory;

    protected $_currencyFactory;

    protected $productCollectionFactory;

    private $collectionProvider;

    private $productCollectionPrepareStrategyProvider;

    protected $_productRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        AttributeCollectionFactory $attributeCollectionFactory,
        Visibility $catalogProductVisibility,
        Config $catalogConfig,
        CurrencyFactory $currencyFactory,
        ProductFactory $productFactory,
        StoreManagerInterface $storeManager,
        ProductCollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        AdvancedFactory $advancedFactory,
        array $data = [],
        ItemCollectionProviderInterface $collectionProvider = null,
        ProductCollectionPrepareStrategyProvider $productCollectionPrepareStrategyProvider = null
    ) {
        $this->_productRepository = $productRepository;
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_catalogConfig = $catalogConfig;
        $this->_currencyFactory = $currencyFactory;
        $this->_productFactory = $productFactory;
        $this->_storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->collectionProvider = $collectionProvider;
        $this->productCollectionPrepareStrategyProvider = $productCollectionPrepareStrategyProvider
            ?: ObjectManager::getInstance()->get(ProductCollectionPrepareStrategyProvider::class);
        parent::__construct(
            $context,
            $registry,
            $advancedFactory->create(),
            $this->resolveProductCollection(),
            $data
        );
    }

    public function getAttributes()
    {
        $attributes = $this->getData('attributes');
        if ($attributes === null) {
            $product = $this->_productFactory->create();
            $attributes = $this->_attributeCollectionFactory
                ->create()
                ->addHasOptionsFilter()
                ->addDisplayInAdvancedSearchFilter()
                ->addStoreLabel($this->_storeManager->getStore()->getId())
                ->setOrder('main_table.attribute_id', 'asc')
                ->load();
            foreach ($attributes as $attribute) {
                $attribute->setEntity($product->getResource());
            }
            $this->setData('attributes', $attributes);
        }
        return $attributes;
    }

    private function resolveProductCollection()
    {
        return (null === $this->collectionProvider)
            ? $this->productCollectionFactory->create()
            : $this->collectionProvider->getCollection();
    }
    public function getAllAttributes()
    {
        $product = $this->_productRepository->get('');
    }
}
