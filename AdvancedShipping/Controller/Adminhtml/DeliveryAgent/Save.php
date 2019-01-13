<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Controller\Adminhtml\DeliveryAgent;


use Codilar\AdvancedShipping\Api\DeliveryAgentManagementInterface;
use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent as ResourceModel;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    /**
     * @var DeliveryAgentManagementInterface
     */
    private $deliveryAgentManagement;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var DeliveryAgentRepositoryInterface
     */
    private $deliveryAgentRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DeliveryAgentManagementInterface $deliveryAgentManagement
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DeliveryAgentManagementInterface $deliveryAgentManagement,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository,
        DataPersistorInterface $dataPersistor
    )
    {
        parent::__construct($context);
        $this->deliveryAgentManagement = $deliveryAgentManagement;
        $this->dataPersistor = $dataPersistor;
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
        $data = $this->getRequest()->getPostValue();
        $result = $this->resultRedirectFactory->create();
        $id = $data[ResourceModel::ID_FIELD_NAME];
        try {
            if ($id) {
                $model = $this->deliveryAgentRepository->load($id);
                if (!strlen($data['password'])) {
                    unset($data['password']);
                    $model->addData($data);
                    $model = $this->deliveryAgentRepository->save($model);
                } else {
                    $model->addData($data);
                    $model = $this->deliveryAgentManagement->create($model, $data['password']);
                }
            } else {
                unset($data[ResourceModel::ID_FIELD_NAME]);
                $model = $this->deliveryAgentRepository->create();
                $model->setData($data);
                $model = $this->deliveryAgentManagement->create($model, $data['password']);
            }

            $this->messageManager->addSuccessMessage(__("Delivery agent \"%1\" saved successfully", $model->getUsername()));
            $result->setPath('*/*');
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
            $this->dataPersistor->set('delivery_agent', $data);
            $result->setPath('*/*/edit', [ 'id' => $id ]);
        }
        return $result;
    }
}