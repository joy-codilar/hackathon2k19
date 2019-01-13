<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model\DeliveryAgent;


use Codilar\AdvancedShipping\Api\DeliveryAgentRepositoryInterface;
use Codilar\AdvancedShipping\Model\DeliveryAgent;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{

    protected $loadedData;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param DeliveryAgentRepositoryInterface $deliveryAgentRepository
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DeliveryAgentRepositoryInterface $deliveryAgentRepository,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $deliveryAgentRepository->getCollection();
        $this->dataPersistor = $dataPersistor;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var DeliveryAgent $deliveryAgent */
        foreach ($items as $deliveryAgent) {
            $deliveryAgent->setPassword('');
            $this->loadedData[$deliveryAgent->getId()] = $deliveryAgent->getData();
        }

        $data = $this->dataPersistor->get('delivery_agent');
        if (!empty($data)) {
            $deliveryAgent = $this->collection->getNewEmptyItem();
            $deliveryAgent->setData($data);
            $this->loadedData[$deliveryAgent->getId()] = $deliveryAgent->getData();
            $this->dataPersistor->clear('delivery_agent');
        }

        return $this->loadedData;
    }
}