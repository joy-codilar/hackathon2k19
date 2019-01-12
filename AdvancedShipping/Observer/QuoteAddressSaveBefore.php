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

class QuoteAddressSaveBefore implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * QuoteAddressSaveBefore constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->getIsEnabled()) {
            /** @var \Magento\Quote\Model\Quote\Address $address */
            $address = $observer->getEvent()->getData('quote_address');
            $address->setData('lat_lng', $address->getExtensionAttributes()->getLatLng());
        }
    }
}