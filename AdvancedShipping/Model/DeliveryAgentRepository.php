<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model;


use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterfaceFactory as ModelFactory;
use Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent as ResourceModel;
use Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent\CollectionFactory;

class DeliveryAgentRepository implements DeliveryAgentRepositoryInterface
{
    /**
     * @var ModelFactory
     */
    private $modelFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * DeliveryAgentRepository constructor.
     * @param ModelFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory
    )
    {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param mixed $value
     * @param string|null $field
     * @return \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null)
    {
        $model = $this->create();
        $this->resourceModel->load($model, $value, $field);
        if (!$model->getId()) {
            throw NoSuchEntityException::singleField($field, $value);
        }
        return $model;
    }

    /**
     * @return \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface
     */
    public function create()
    {
        return $this->modelFactory->create();
    }

    /**
     * @param \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface $model
     * @return \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface
     * @throws CouldNotSaveException
     */
    public function save($model)
    {
        try {
            $this->validateUsername($model);
            $this->resourceModel->save($model);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("Some error occurred while saving the model"));
        }
        return $model;
    }

    /**
     * @param \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface $model
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete($model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__("Some error occurred while deleting the model"));
        }
        return $this;
    }

    /**
     * @return \Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent\Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @param \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface $model
     * @throws AlreadyExistsException
     */
    protected function validateUsername($model) {
        if ($this->getCollection()
            ->addFieldToFilter('username', $model->getUsername())
            ->addFieldToFilter(ResourceModel::ID_FIELD_NAME, ['neq' => $model->getId()])
            ->getSize()) {
            throw new AlreadyExistsException(__("A user with the same username already exists"));
        }
    }
}