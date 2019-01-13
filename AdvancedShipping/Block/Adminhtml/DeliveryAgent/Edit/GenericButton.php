<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block\Adminhtml\DeliveryAgent\Edit;

use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     */
    public function __construct(
        Context $context,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository
    )
    {
        $this->context = $context;
        $this->deliveryAgentRepository = $deliveryAgentRepository;
    }

    /**
     * @return int|null
     */
    public function getDeliveryAgentId() {
        try {
            return $this->deliveryAgentRepository->load(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}