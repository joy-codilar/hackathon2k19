<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Block;


use Magento\Framework\View\Element\Template;

class Config extends Template
{
    /**
     * @var \Codilar\AdvancedShipping\Model\Config
     */
    private $configModel;

    /**
     * Config constructor.
     * @param Template\Context $context
     * @param \Codilar\AdvancedShipping\Model\Config $configModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Codilar\AdvancedShipping\Model\Config $configModel,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->configModel = $configModel;
    }

    /**
     * @return \Codilar\AdvancedShipping\Model\Config
     */
    public function getConfigModel()
    {
        return $this->configModel;
    }
}