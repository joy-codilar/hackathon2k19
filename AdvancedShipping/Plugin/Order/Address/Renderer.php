<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Plugin\Order\Address;

use Codilar\AdvancedShipping\Block\ShowMap;
use Codilar\AdvancedShipping\Model\Config;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Model\Order\Address\Renderer as Subject;
use Magento\Sales\Model\Order\Address;

class Renderer
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
     * Renderer constructor.
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
     * Format address in a specific way
     *
     * @param Subject $subject
     * @param $addressString
     * @param Address $address
     * @return string|null
     */
    public function afterFormat(Subject $subject, $addressString, Address $address)
    {
        if ($this->config->getShowMap()) {
            /** @var ShowMap $showMapBlock */
            $showMapBlock = $this->layout->createBlock(ShowMap::class);
            $showMapBlock->setAddress($address);
            $addressString = $showMapBlock->toHtml() . $addressString;
        }
        return $addressString;
    }
}