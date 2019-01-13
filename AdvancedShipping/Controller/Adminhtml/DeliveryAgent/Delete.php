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
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    /**
     * Delete constructor.
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
        $result = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        try {
            $model = $this->deliveryAgentRepository->load($id);
            $this->deliveryAgentRepository->delete($model);
            $this->messageManager->addSuccessMessage(__("Delivery Agent deleted successfully"));
            $result->setPath('*/*');
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
            $result->setPath('*/*/edit', [ 'id' => $id ]);
        }
        return $result;
    }
}