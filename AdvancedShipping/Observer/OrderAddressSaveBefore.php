<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Observer;


use Codilar\AdvancedShipping\Model\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\Quote\Model\ResourceModel\Quote\Address as QuoteAddressResource;

class OrderAddressSaveBefore implements ObserverInterface
{
    /**
     * @var AddressFactory
     */
    private $addressFactory;
    /**
     * @var QuoteAddressResource
     */
    private $quoteAddressResource;
    /**
     * @var Config
     */
    private $config;

    /**
     * OrderAddressSaveBefore constructor.
     * @param AddressFactory $addressFactory
     * @param QuoteAddressResource $quoteAddressResource
     * @param Config $config
     */
    public function __construct(
        AddressFactory $addressFactory,
        QuoteAddressResource $quoteAddressResource,
        Config $config
    )
    {
        $this->addressFactory = $addressFactory;
        $this->quoteAddressResource = $quoteAddressResource;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->getIsEnabled()) {
            /** @var \Magento\Sales\Model\Order\Address $address */
            $address = $observer->getEvent()->getData('address');
            /** @var Address $quoteAddress */
            $quoteAddress = $this->addressFactory->create();
            $this->quoteAddressResource->load($quoteAddress, $address->getData('quote_address_id'));
            if ($quoteAddress->getId()) {
                $address->setData('lat_lng', $quoteAddress->getData('lat_lng'));
            }
        }
    }
}