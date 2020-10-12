<?php

namespace Magepow\Search\Helper;
use Magento\Framework\App\ObjectManager;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $configModule;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
        $this->configModule = $this->getConfig(strtolower($this->_getModuleName()));
    }

    public function getConfig($cfg='')
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $this->scopeConfig;
    }

    public function getConfigModule($cfg='', $value=null)
    {
        $values = $this->configModule;
        if( !$cfg ) return $values;
        $config  = explode('/', $cfg);
        $end     = count($config) - 1;
        foreach ($config as $key => $vl) {
            if( isset($values[$vl]) ){
                if( $key == $end ) {
                    $value = $values[$vl];
                }else {
                    $values = $values[$vl];
                }
            }

        }
        return $value;
    }

    public function getConfigArraySerialized($value)
    {
        $tmp = json_decode($value, true);
        if(json_last_error() == JSON_ERROR_NONE){
            $value = $tmp;
        } else {
            $value = @unserialize($value);
        }
        return $value;
    }

    public function getConfigValue($configPath, $store = null)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSerializedConfigValue($configPath, $store = null)
    {
        $value = $this->getConfigValue($configPath, $store);

        if (empty($value)) {
            return false;
        }

        if ($this->isSerialized($value)) {
            $unserializer = ObjectManager::getInstance()->get(\Magento\Framework\Unserialize\Unserialize::class);
        } else {
            $unserializer = ObjectManager::getInstance()->get(\Magento\Framework\Serialize\Serializer\Json::class);
        }

        return $unserializer->unserialize($value);
    }
    private function isSerialized($value)
    {
        return (boolean) preg_match('/^((s|i|d|b|a|O|C):|N;)/', $value);
    }
}
