<?php

namespace Magepow\Search\Block;

use Magento\Framework\View\Element\Template;

class Sample extends Template
{
    protected $helper;

    public function __construct(Template\Context $context, \Magepow\Search\Helper\Data $helper, array $data = [])
    {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }
    public function getArraySerialized()
    {
//        $responsiveItems = $this->helper->getSerializedConfigValue('attribute_search/source_option/attribute_option');
//        //process value according to your needs...
//        return json_encode($responsiveItems);
    }
}
