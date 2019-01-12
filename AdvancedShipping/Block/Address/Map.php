<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block\Address;


use Codilar\AdvancedShipping\Helper\Gmaps;
use Codilar\AdvancedShipping\Model\Config;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderAddressRepositoryInterface;

class Map extends Template
{

    protected $_template = "Codilar_AdvancedShipping::address/map.phtml";

    /**
     * @var Config
     */
    private $config;
    /**
     * @var Gmaps
     */
    private $gmapsHelper;
    /**
     * @var CountryFactory
     */
    private $countryFactory;

    /**
     * Map constructor.
     * @param Template\Context $context
     * @param Config $config
     * @param Gmaps $gmapsHelper
     * @param CountryFactory $countryFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        Gmaps $gmapsHelper,
        CountryFactory $countryFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->gmapsHelper = $gmapsHelper;
        $this->countryFactory = $countryFactory;
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderAddressInterface
     */
    protected function getAddress() {
        return $this->config->getValue('address');
    }

    /**
     * @return array
     */
    public function getLatLng() {
        $latLng = explode(",", $this->getAddress()->getData('lat_lng'));
        if (count($latLng) === 2) {
            return [
                'lat'   =>  (double)$latLng[0],
                'lng'   =>  (double)$latLng[1]
            ];
        } else {
            return $this->gmapsHelper->getLatLng($this->formatAddress());
        }
    }

    /**
     * @return string
     */
    protected function formatAddress() {
        /** @var \Magento\Sales\Model\Order\Address $address */
        $address = $this->getAddress();
        $street = implode(', ', $address->getStreet());
        $country = $this->countryFactory->create()->loadByCode($address->getCountryId())->getName();
        return "{$street} {$address->getCompany()} {$address->getCity()} {$address->getRegion()} {$country}";
    }


}