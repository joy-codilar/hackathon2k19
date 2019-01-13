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
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository
    )
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->deliveryAgentRepository = $deliveryAgentRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->deliveryAgentRepository->getCollection());

        $deletedEntries = 0;

        foreach ($collection as $item) {
            try {
                $this->deliveryAgentRepository->delete($item);
                $deletedEntries++;
            } catch (CouldNotDeleteException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $deletedEntries));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}