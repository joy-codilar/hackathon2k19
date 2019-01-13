<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model;


use Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface;
use Codilar\AdvancedShipping\Api\DeliveryAgentManagementInterface;
use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Codilar\AdvancedShipping\Model\DeliveryAgent\Session as DeliveryAgentSession;
use Magento\Framework\Exception\NoSuchEntityException;

class DeliveryAgentManagement implements DeliveryAgentManagementInterface
{
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;
    /**
     * @var DeliveryAgentSession
     */
    private $deliveryAgentSession;

    /**
     * DeliveryAgentManagement constructor.
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     * @param DeliveryAgentSession $deliveryAgentSession
     */
    public function __construct(
        DeliveryAgentRepositoryInterface $deliveryAgentRepository,
        DeliveryAgentSession $deliveryAgentSession
    )
    {
        $this->deliveryAgentRepository = $deliveryAgentRepository;
        $this->deliveryAgentSession = $deliveryAgentSession;
    }

    /**
     * @param DeliveryAgentInterface $deliveryAgent
     * @param string $password
     * @return DeliveryAgentInterface
     * @throws CouldNotSaveException
     */
    public function create(DeliveryAgentInterface $deliveryAgent, $password)
    {
        if (!strlen($password)) {
            throw new CouldNotSaveException(__("Cannot save delivery agent without password"));
        }
        $deliveryAgent->setPassword($this->doPasswordHash($password));
        $this->deliveryAgentRepository->save($deliveryAgent);
        return $deliveryAgent;
    }

    /**
     * @param string $username
     * @param string $password
     * @return DeliveryAgentInterface
     * @throws LocalizedException
     */
    public function login($username, $password)
    {
        try {
            $deliveryAgent = $this->deliveryAgentRepository->load($username, "username");
            if ($this->doPasswordHash($password) !== $deliveryAgent->getPassword()) {
                throw NoSuchEntityException::doubleField("username", $username, "password", $password);
            }
            $this->deliveryAgentSession->setDeliveryAgentAsLoggedIn($deliveryAgent);
            return $deliveryAgent;
        } catch (NoSuchEntityException $noSuchEntityException) {
            throw new LocalizedException(__("Incorrect username or password"));
        }
    }

    /**
     * @return $this
     */
    public function logout()
    {
        if ($this->deliveryAgentSession->isLoggedIn()) {
            $this->deliveryAgentSession->logout();
        }
        return $this;
    }

    protected function doPasswordHash($plainText) {
        return hash('sha256', $plainText);
    }
}