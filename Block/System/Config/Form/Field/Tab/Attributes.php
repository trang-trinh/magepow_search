<?php

namespace Magepow\Search\Block\System\Config\Form\Field\Tab;

class Attributes extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected $_elementFactory;

    protected $_enabledRenderer;

    protected $_attribute;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Magepow\Search\Model\System\Config\Attribute $attribute,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        $this->_attribute = $attribute;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->addColumn('title', [
            'label' => __('Title'),
            'style' => 'width:180px',
            'class' => 'title',
        ]);

        $this->addColumn('attribute', [
            'label' => __('Attribute Tab'),
            'style' => 'width:116px',
            'class' => 'static_block',
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');

        parent::_construct();
    }
    public function renderCellTemplate($columnName)
    {
        if ($columnName == 'attribute' && isset($this->_columns[$columnName])) {
            $options = $this->_attribute->toOptionArray();
            $element = $this->_elementFactory->create('select');
            $element->setForm(
                $this->getForm()
            )->setName(
                $this->_getCellInputElementName($columnName)
            )->setHtmlId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setValues(
                $options
            );
            $style = '<style>' . $element->getHtmlId() . '{min-width:185px}</style>';
            return str_replace("\n", '', $element->getElementHtml()) . $style;
        }
        return parent::renderCellTemplate($columnName);
    }
}
