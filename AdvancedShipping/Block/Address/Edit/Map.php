<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block\Address\Edit;


use Codilar\AdvancedShipping\Block\Config;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\ResourceModel\Address as AddressResource;


class Map extends Config
{
    /**
     * @var AddressFactory
     */
    private $addressFactory;
    /**
     * @var AddressResource
     */
    private $addressResource;

    /**
     * Map constructor.
     * @param Template\Context $context
     * @param \Codilar\AdvancedShipping\Model\Config $configModel
     * @param AddressFactory $addressFactory
     * @param AddressResource $addressResource
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Codilar\AdvancedShipping\Model\Config $configModel,
        AddressFactory $addressFactory,
        AddressResource $addressResource,
        array $data = []
    )
    {
        parent::__construct($context, $configModel, $data);
        $this->addressFactory = $addressFactory;
        $this->addressResource = $addressResource;
    }

    /**
     * @return \Magento\Customer\Model\Address
     */
    public function getAddress() {
        $address = $this->addressFactory->create();
        $this->addressResource->load($address, $this->getRequest()->getParam('id'));
        return $address;
    }
}