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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Page;
use Magento\Sales\Api\OrderAddressRepositoryInterface;

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
     * @var OrderAddressRepositoryInterface
     */
    private $orderAddressRepository;

    /**
     * Map constructor.
     * @param Context $context
     * @param Config $config
     * @param EncryptorInterface $encryptor
     * @param OrderAddressRepositoryInterface $orderAddressRepository
     */
    public function __construct(
        Context $context,
        Config $config,
        EncryptorInterface $encryptor,
        OrderAddressRepositoryInterface $orderAddressRepository
    )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->encryptor = $encryptor;
        $this->orderAddressRepository = $orderAddressRepository;
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
            $addressId = $this->encryptor->decrypt(urldecode($this->getRequest()->getParam('aid')));
            $address = $this->orderAddressRepository->get($addressId);
            if (!$address->getEntityId()) {
                throw new NotFoundException(__("Address doesn't exist"));
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