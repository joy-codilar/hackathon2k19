<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface DeliveryAgentRepositoryInterface
{
    /**
     * @param mixed $value
     * @param string|null $field
     * @return \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface
     */
    public function create();

    /**
     * @param \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface $model
     * @return \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface
     * @throws CouldNotSaveException
     */
    public function save($model);

    /**
     * @param \Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface $model
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete($model);

    /**
     * @return \Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent\Collection
     */
    public function getCollection();
}