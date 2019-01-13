<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Api;


use Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

interface DeliveryAgentManagementInterface
{
    /**
     * @param DeliveryAgentInterface $deliveryAgent
     * @param string $password
     * @return DeliveryAgentInterface
     * @throws CouldNotSaveException
     */
    public function create(DeliveryAgentInterface $deliveryAgent, $password);

    /**
     * @param string $username
     * @param string $password
     * @return DeliveryAgentInterface
     * @throws LocalizedException
     */
    public function login($username, $password);

    /**
     * @return $this
     */
    public function logout();
}