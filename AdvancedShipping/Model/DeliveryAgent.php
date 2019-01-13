<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model;


use Codilar\AdvancedShipping\Api\Data\DeliveryAgentInterface;
use Magento\Framework\Model\AbstractModel;
use Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent as ResourceModel;

class DeliveryAgent extends AbstractModel implements DeliveryAgentInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::COLUMN_NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::COLUMN_NAME, $name);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getData(self::COLUMN_USERNAME);
    }

    /**
     * @param string $userName
     * @return $this
     */
    public function setUsername($userName)
    {
        return $this->setData(self::COLUMN_USERNAME, $userName);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->getData(self::COLUMN_PASSWORD);
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        return $this->setData(self::COLUMN_PASSWORD, $password);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::COLUMN_CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::COLUMN_UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::COLUMN_UPDATED_AT, $updatedAt);
    }
}