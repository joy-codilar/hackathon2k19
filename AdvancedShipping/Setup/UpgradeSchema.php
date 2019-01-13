<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Setup;


use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Codilar\AdvancedShipping\Model\ResourceModel\DeliveryAgent as ResourceModel;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->createDeliveryAgentTable($setup);

        $column = "delivery_agent_id";
        $definition = [
            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'length'   => 10,
            'nullable' => true,
            'comment'  => 'Delivery Agent ID'
        ];
        $this->addColumnIfNotExists($setup, 'sales_shipment', $column, $definition);

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createDeliveryAgentTable(SchemaSetupInterface $setup) {
        $table = $setup->getConnection()->newTable(ResourceModel::TABLE_NAME)
            ->addColumn(
                ResourceModel::ID_FIELD_NAME,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity'  =>  true,
                    'primary'   =>  true,
                    'nullable'  =>  false,
                    'unsigned'  =>  true
                ],
                "Entity ID"
            )->addColumn(
                "name",
                Table::TYPE_TEXT,
                255,
                [ 'nullable'  =>  false ],
                "Delivery Agent Name"
            )->addColumn(
                "username",
                Table::TYPE_TEXT,
                255,
                [ 'nullable'  =>  false ],
                "Delivery Agent Username"
            )->addColumn(
                "password",
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => false ],
                "Hashed Password"
            )->addColumn(
                "created_at",
                Table::TYPE_TIMESTAMP,
                null,
                [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT ],
                "Created At"
            )->addColumn(
                "updated_at",
                Table::TYPE_TIMESTAMP,
                null,
                [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE ],
                "Updated At"
            )->addIndex(
                $setup->getIdxName(
                    ResourceModel::TABLE_NAME,
                    ['username'],
                    Mysql::INDEX_TYPE_UNIQUE
                ),
                ['username'],
                ['type' => Mysql::INDEX_TYPE_UNIQUE]
            )->setComment("Advanced Shipping Delivery Agent Table");
        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param string $table
     * @param string $column
     * @param array $definition
     */
    protected function addColumnIfNotExists(SchemaSetupInterface $setup, $table, $column, $definition) {
        $table = $setup->getTable($table);
        if (!$setup->getConnection()->tableColumnExists($table, $column)) {
            $setup->getConnection()->addColumn($table, $column, $definition);
        }
    }
}