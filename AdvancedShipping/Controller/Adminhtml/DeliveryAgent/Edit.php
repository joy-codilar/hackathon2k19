<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Controller\Adminhtml\DeliveryAgent;


use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;

class Edit extends Action
{
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     */
    public function __construct(
        Action\Context $context,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository
    )
    {
        parent::__construct($context);
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
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $deliveryAgent = $this->deliveryAgentRepository->load($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
            }
            $title = __("Edit Delivery Agent #%1", $deliveryAgent->getId());
        } else {
            $title = __("New Delivery Agent");
        }
        /** @var Page $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $result->getConfig()->getTitle()->set($title);
        return $result;
    }
}