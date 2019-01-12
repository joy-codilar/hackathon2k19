<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Helper;


use Codilar\AdvancedShipping\Model\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\CurlFactory;

class Gmaps extends AbstractHelper
{

    const GMAPS_API_ENDPOINT = "https://maps.googleapis.com/maps/api/geocode/json";

    /**
     * @var Config
     */
    private $config;
    /**
     * @var CurlFactory
     */
    private $curlFactory;

    /**
     * Gmaps constructor.
     * @param Context $context
     * @param Config $config
     * @param CurlFactory $curlFactory
     */
    public function __construct(
        Context $context,
        Config $config,
        CurlFactory $curlFactory
    )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->curlFactory = $curlFactory;
    }

    protected function getRequestUrl($data) {
        $data['key'] = $this->config->getApiKey();
        return self::GMAPS_API_ENDPOINT. '?' . http_build_query($data);
    }

    /**
     * @param string $address
     * @return array
     */
    public function getLatLng($address) {
        $curl = $this->curlFactory->create();
        $curl->get($this->getRequestUrl(['address'  =>  $address]));
        $response = \json_decode($curl->getBody(), true);
        if (is_array($response['results']) && count($response['results'])) {
            return $response['results'][0]['geometry']['location'];
        } else {
            return $this->config->getDefaultMarker();
        }
    }
}