<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Plugin\Customer\Address;

use Codilar\AdvancedShipping\Block\ShowMap;
use Codilar\AdvancedShipping\Model\Config;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Block\Address\Book as Subject;
use Magento\Framework\View\LayoutInterface;

class Book
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * Book constructor.
     * @param Config $config
     * @param LayoutInterface $layout
     */
    public function __construct(
        Config $config,
        LayoutInterface $layout
    )
    {
        $this->config = $config;
        $this->layout = $layout;
    }

    /**
     * Render an address as HTML and return the result
     *
     * @param Subject $subject
     * @param string $addressString
     * @param AddressInterface $address
     * @return string
     */
    public function afterGetAddressHtml(Subject $subject, $addressString, AddressInterface $address = null)
    {

        if ($this->config->getShowMap()) {
            /** @var ShowMap $showMapBlock */
            $showMapBlock = $this->layout->createBlock(ShowMap::class);
            $showMapBlock->setAddress($address)->setType('customer');
            $addressString = $showMapBlock->toHtml() . $addressString;
        }

        return $addressString;
    }
}