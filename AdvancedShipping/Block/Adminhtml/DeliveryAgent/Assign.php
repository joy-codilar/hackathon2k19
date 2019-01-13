<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block\Adminhtml\DeliveryAgent;


use Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface;
use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Backend\Block\Template;
use Magento\Sales\Api\ShipmentRepositoryInterface;

class Assign extends Template
{
    /**
     * @var ShipmentRepositoryInterface
     */
    private $shipmentRepository;
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    /**
     * Assign constructor.
     * @param Template\Context $context
     * @param ShipmentRepositoryInterface $shipmentRepository
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ShipmentRepositoryInterface $shipmentRepository,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->shipmentRepository = $shipmentRepository;
        $this->deliveryAgentRepository = $deliveryAgentRepository;
    }

    /**
     * @return \Magento\Sales\Api\Data\ShipmentInterface
     */
    public function getShipment() {
        return $this->shipmentRepository->get($this->getRequest()->getParam('shipment_id'));
    }

    /**
     * @return DeliveryAgentInterface[]
     */
    public function getDeliveryAgents() {
        return $this->deliveryAgentRepository->getCollection()->getItems();
    }

    /**
     * @return string
     */
    public function getAssignPostUrl() {
        return $this->getUrl('advanced_shipping/deliveryAgent_shipment/assign');
    }
}