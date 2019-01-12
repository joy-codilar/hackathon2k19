<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block\Address;


use Codilar\AdvancedShipping\Model\Config;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderAddressRepositoryInterface;

class Map extends Template
{

    protected $_template = "Codilar_AdvancedShipping::address/map.phtml";

    /**
     * @var OrderAddressRepositoryInterface
     */
    private $orderAddressRepository;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * Map constructor.
     * @param Template\Context $context
     * @param OrderAddressRepositoryInterface $orderAddressRepository
     * @param Config $config
     * @param EncryptorInterface $encryptor
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        OrderAddressRepositoryInterface $orderAddressRepository,
        Config $config,
        EncryptorInterface $encryptor,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->orderAddressRepository = $orderAddressRepository;
        $this->config = $config;
        $this->encryptor = $encryptor;
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderAddressInterface
     */
    protected function getAddress() {
        return $this->orderAddressRepository->get(
            $this->encryptor->decrypt(
                urldecode($this->getRequest()->getParam('aid'))
            )
        );
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
            return $this->config->getDefaultMarker();
        }
    }


}