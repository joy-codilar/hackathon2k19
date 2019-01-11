<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model;


use Codilar\Core\Model\AbstractConfig;

class Config extends AbstractConfig
{
    /**
     * @return bool
     */
    public function getIsEnabled() {
        return (bool)$this->getValue('advanced_shipping/general/enable');
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->getValue('advanced_shipping/general/api_key');
    }
}