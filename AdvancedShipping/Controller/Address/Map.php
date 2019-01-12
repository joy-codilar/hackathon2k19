<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Controller\Address;


use Codilar\AdvancedShipping\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Page;
use Magento\Customer\Model\AddressFactory as CustomerAddressFactory;
use Magento\Customer\Model\Address as CustomerAddress;
use Magento\Customer\Model\ResourceModel\Address as CustomerAddressResource;
use Magento\Sales\Model\Order\AddressFactory as OrderAddressFactory;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\ResourceModel\Order\Address as OrderAddressResource;


class Map extends Action
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var EncryptorInterface
     */
    private $encryptor;
    /**
     * @var CustomerAddressFactory
     */
    private $customerAddressFactory;
    /**
     * @var OrderAddressFactory
     */
    private $orderAddressFactory;
    /**
     * @var CustomerAddressResource
     */
    private $customerAddressResource;
    /**
     * @var OrderAddressResource
     */
    private $orderAddressResource;

    /**
     * Map constructor.
     * @param Context $context
     * @param Config $config
     * @param EncryptorInterface $encryptor
     * @param CustomerAddressFactory $customerAddressFactory
     * @param OrderAddressFactory $orderAddressFactory
     * @param CustomerAddressResource $customerAddressResource
     * @param OrderAddressResource $orderAddressResource
     */
    public function __construct(
        Context $context,
        Config $config,
        EncryptorInterface $encryptor,
        CustomerAddressFactory $customerAddressFactory,
        OrderAddressFactory $orderAddressFactory,
        CustomerAddressResource $customerAddressResource,
        OrderAddressResource $orderAddressResource
    )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->encryptor = $encryptor;
        $this->customerAddressFactory = $customerAddressFactory;
        $this->orderAddressFactory = $orderAddressFactory;
        $this->customerAddressResource = $customerAddressResource;
        $this->orderAddressResource = $orderAddressResource;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        if ($this->config->getIsEnabled()) {
            try {
                $addressId = $this->encryptor->decrypt(urldecode($this->getRequest()->getParam('aid')));
                if ($this->getRequest()->getParam('type', 'order') === "customer") {
                    $address = $this->customerAddressFactory->create();
                    $this->customerAddressResource->load($address, $addressId);
                } else {
                    $address = $this->orderAddressFactory->create();
                    $this->orderAddressResource->load($address, $addressId);
                }
                if (!$address->getEntityId()) {
                    throw new LocalizedException(__("Address doesn't exist"));
                }
                $this->config->setValue('address', $address);
            } catch (LocalizedException $localizedException) {
                throw new NotFoundException(__($localizedException->getMessage()));
            }
            /** @var Page $page */
            $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $page->getConfig()->getTitle()->set(__("Map for Address #%1", $address->getEntityId()));
            return $page;
        } else {
            throw new NotFoundException(__("Advanced Shipping module is disabled"));
        }
    }
}