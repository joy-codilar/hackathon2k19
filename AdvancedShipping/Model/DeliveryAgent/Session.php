<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model\DeliveryAgent;

use Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface;
use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\Storage;
use Magento\Framework\Session\StorageInterface;

class Session extends SessionManager
{

    const SESSION_NAMESPACE = "advanced_shipping_delivery_agent";
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    private $loggedInDeliveryAgent;

    /**
     * Session constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Session\SidResolverInterface $sidResolver
     * @param \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
     * @param \Magento\Framework\Session\SaveHandlerInterface $saveHandler
     * @param \Magento\Framework\Session\ValidatorInterface $validator
     * @param \Magento\Framework\Session\StorageInterface $storage
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\Session\Config\ConfigInterface $sessionConfig,
        \Magento\Framework\Session\SaveHandlerInterface $saveHandler,
        \Magento\Framework\Session\ValidatorInterface $validator,
        \Magento\Framework\Session\StorageInterface $storage,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository
    ) {
        parent::__construct($request, $sidResolver, $sessionConfig, $saveHandler, $validator, $storage, $cookieManager, $cookieMetadataFactory, $appState);
        $eventManager->dispatch(self::SESSION_NAMESPACE.'_session_init', [self::SESSION_NAMESPACE => $this]);
        $this->deliveryAgentRepository = $deliveryAgentRepository;
        $this->loggedInDeliveryAgent = null;
    }

    /**
     * @return StorageInterface|Storage
     */
    protected function getStorage() {
        return $this->storage;
    }

    /**
     * @param int $id
     * @return $this
     */
    protected function setLoggedInDeliveryAgentId($id) {
        $this->getStorage()->setData('delivery_agent_id', $id);
        return $this;
    }

    /**
     * @return int|null
     */
    protected function getLoggedInDeliveryAgentId() {
        return $this->getStorage()->getData('delivery_agent_id');
    }

    /**
     * @param DeliveryAgentInterface $deliveryAgent
     * @return $this
     */
    public function setDeliveryAgentAsLoggedIn(DeliveryAgentInterface $deliveryAgent) {
        $this->setLoggedInDeliveryAgentId($deliveryAgent->getId());
        $this->loggedInDeliveryAgent = null;
        return $this;
    }

    /**
     * @return $this
     */
    public function logout() {
        $this->setLoggedInDeliveryAgentId(null);
        return $this;
    }

    public function getLoggedInDeliveryAgent() {
        if (!$this->loggedInDeliveryAgent) {
            try {
                $id = $this->getLoggedInDeliveryAgentId();
                $this->loggedInDeliveryAgent = $this->deliveryAgentRepository->load($id);
            } catch (NoSuchEntityException $e) {
                $this->setLoggedInDeliveryAgentId(null);
                $this->loggedInDeliveryAgent = null;
            }
        }
        return $this->loggedInDeliveryAgent;
    }

    public function isLoggedIn() {
        return $this->loggedInDeliveryAgent instanceof DeliveryAgentInterface;
    }
}