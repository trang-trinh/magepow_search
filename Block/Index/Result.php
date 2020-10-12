<?php
namespace Magepow\Search\Block\Index;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\ActionInterface;

class Result extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $_collection;
    protected $_productCollection;
    protected $_helper;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magepow\Search\Helper\Data $helper,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        Collection $collection,
        array $data = []
    ) {
        $this->imageBuilder = $context->getImageBuilder();
        $this->_collection = $collection;
        $this->_helper = $helper;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
        $this->pageConfig->getTitle()->set(__($this
            ->_helper
            ->getConfig(
                'magepow_search_settings/magepow_search_products/title'
            )));
    }

    protected function getProducts()
    {
        $this->_collection->clear()->getSelect()->reset('where');
        $limit = $this->_helper
            ->getConfig(
                'magepow_search_settings/magepow_search_products/limit'
            );
        if ($limit == "" || $limit == 0) {
            $limit = 10;
        }
        $dropdownlimit = $this->_scopeConfig
            ->getValue(
                'catalog/frontend/grid_per_page_values',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        $dropdownlimit = preg_replace('/[^0-9,]/', '', $dropdownlimit);
        $limitarray = explode(",", $dropdownlimit);
        $limitarray = array_map('trim', $limitarray);
        $keyvaluearray = array_combine($limitarray, $limitarray);
        if (!in_array($limit, $limitarray)) {
            $keyvaluearray[$limit] = $limit;
        }
        $keyvaluearray = array_filter($keyvaluearray);
        ksort($keyvaluearray);

        if ($this->_request->getParam('limit')) {
            $limit = $this->_request->getParam('limit');
        }
        $limit = abs($limit);
        $attributeCode1 = $this->getRequest()->getParam('attribute_code1');
        $attributeOption1 = $this->getRequest()->getParam('attribute_option1');
        $attributeCode2 = $this->getRequest()->getParam('attribute_code2');
        $attributeOption2 = $this->getRequest()->getParam('attribute_option2');
        $collection = $this->_collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToFilter($attributeCode1,$attributeOption1, 'left')
            ->addAttributeToFilter($attributeCode2,$attributeOption2, 'left');

        $pager = $this->getLayout()
            ->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'magepow_search.grid.record.pager'
            )
            ->setLimit($limit)
            ->setCollection($collection);
        $pager->setAvailableLimit($keyvaluearray);
        $this->setChild('pager', $pager);

        $this->_productCollection = $collection;
        return $this->_productCollection;
    }
    public function getLoadedProductCollection()
    {
        return $this->getProducts();
    }
    public function getToolbarHtml()
    {
        return $this->getChildHtml('pager');
    }
    public function getMode()
    {
        return 'grid';
    }
    public function getImageHelper()
    {
        return $this->_imageHelper;
    }

    public function getAddToCartPostParams(
        \Magento\Catalog\Model\Product $product
    ) {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
