<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block;


use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
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
     * @var string
     */
    protected $type;

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
            $this->mapId = "advanced-shipping-show-map-" . $this->getAddress()->getId() . uniqid();
        }
        return $this->mapId;
    }

    /**
     * @param Address|AddressInterface $address
     * @return $this
     */
    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    /**
     * @return Address|AddressInterface|null
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getMapUrl() {
        return $this->url->getUrl('advanced_shipping/address/map', ['aid' => urlencode($this->encryptor->encrypt($this->getAddress()->getId())), 'type' => $this->getType()]);
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getType()
    {
        if (!$this->type) {
            throw new LocalizedException(__("Address type not set"));
        }
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}