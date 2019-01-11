<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AbstractConfig
{

    protected $data = [];

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * AbstractConfig constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $path
     * @param string $scope
     * @return mixed
     */
    public function getValue($path, $scope = ScopeInterface::SCOPE_STORE) {
        $key = $path.$scope;
        if (!array_key_exists($key, $this->data)) {
            $this->data[$key] = $this->scopeConfig->getValue($path, $scope);
        }
        return $this->data[$key];
    }
}