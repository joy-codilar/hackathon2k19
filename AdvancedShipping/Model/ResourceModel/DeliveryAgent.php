<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class DeliveryAgent extends AbstractDb
{

    const TABLE_NAME = "advanced_shipping_delivery_agent";
    const ID_FIELD_NAME = "advanced_shipping_delivery_agent_id";

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD_NAME);
    }
}