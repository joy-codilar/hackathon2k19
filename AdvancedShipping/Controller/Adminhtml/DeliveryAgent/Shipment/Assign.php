<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Controller\Adminhtml\DeliveryAgent\Shipment;


use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\ShipmentRepositoryInterface;

class Assign extends Action
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
     * @param Action\Context $context
     * @param ShipmentRepositoryInterface $shipmentRepository
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     */
    public function __construct(
        Action\Context $context,
        ShipmentRepositoryInterface $shipmentRepository,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository
    )
    {
        parent::__construct($context);
        $this->shipmentRepository = $shipmentRepository;
        $this->deliveryAgentRepository = $deliveryAgentRepository;
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
        try {
            $deliveryAgent = $this->deliveryAgentRepository->load($this->getRequest()->getParam('delivery_agent_id'));
            $shipment = $this->shipmentRepository->get($this->getRequest()->getParam('shipment_id'));
            $shipment->setData('delivery_agent_id', $deliveryAgent->getId());
            $this->shipmentRepository->save($shipment);
            $data = [
                'status'    =>  true,
                'message'   =>  __("Delivery Agent assigned successfully")
            ];
        } catch (LocalizedException $localizedException) {
            $data = [
                'status'    =>  false,
                'message'   =>  $localizedException->getMessage()
            ];
        }
        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData($data);
        return $response;
    }
}