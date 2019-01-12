<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block;


use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order\Address;


class ShowMap extends Template
{
    protected $_template = "Codilar_AdvancedShipping::show_map.phtml";

    /**
     * @var Address|null
     */
    protected $address = null;
    /**
     * @var EncryptorInterface
     */
    private $encryptor;
    /**
     * @var \Magento\Framework\Url
     */
    private $url;

    /**
     * @var string
     */
    private $mapId;

    /**
     * ShowMap constructor.
     * @param Template\Context $context
     * @param EncryptorInterface $encryptor
     * @param \Magento\Framework\Url $url
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        EncryptorInterface $encryptor,
        \Magento\Framework\Url $url,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->encryptor = $encryptor;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getMapId() {
        if (!$this->mapId) {
            $this->mapId = "advanced-shipping-show-map-" . $this->getAddress()->getEntityId() . uniqid();
        }
        return $this->mapId;
    }

    /**
     * @param Address $address
     * @return $this
     */
    public function setAddress(Address $address) {
        $this->address = $address;
        return $this;
    }

    /**
     * @return Address|null
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getMapUrl() {
        return $this->url->getUrl('advanced_shipping/address/map', ['aid' => urlencode($this->encryptor->encrypt($this->getAddress()->getId()))]);
    }

}