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

    /**
     * @return int
     */
    public function getZoom() {
        return (int)$this->getValue('advanced_shipping/general/zoom');
    }

    /**
     * @return array
     */
    public function getDefaultMarker() {
        @list($lat, $lng) = explode(",", $this->getValue('advanced_shipping/general/default_marker'));
        return [
            "lat"   =>  $lat ? (double)$lat : 12.9716,
            "lng"   =>  $lng ? (double)$lng : 77.5946
        ];
    }

    /**
     * @return bool
     */
    public function getShowMap() {
        return $this->getIsEnabled() && (bool)$this->getValue('advanced_shipping/display/show_map');
    }
}