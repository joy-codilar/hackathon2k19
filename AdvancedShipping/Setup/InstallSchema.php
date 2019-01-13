<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $column = "lat_lng";
        $definition = [
            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'   => 100,
            'nullable' => true,
            'comment'  => 'Latitude & Longitude'
        ];

        $this->addColumnIfNotExists($setup, "quote_address", $column, $definition);
        $this->addColumnIfNotExists($setup, "sales_order_address", $column, $definition);
        $setup->endSetup();
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