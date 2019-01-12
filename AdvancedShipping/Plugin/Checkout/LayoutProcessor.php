<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Plugin\Checkout;


use Codilar\AdvancedShipping\Model\Config;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * LayoutProcessor constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {

        if ($this->config->getIsEnabled()) {
            $attributeCode = 'lat_lng';

            $shippingAddressField = $this->getFieldData($attributeCode, "shippingAddress");
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$attributeCode] = $shippingAddressField;

            $paymentForms = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'];
            foreach ($paymentForms as $paymentGroup => $groupConfig) {
                if (isset($groupConfig['component']) && $groupConfig['component'] === 'Magento_Checkout/js/view/billing-address') {
                    $billingAddressField = $this->getFieldData($attributeCode, $groupConfig["dataScopePrefix"]);
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children'][$attributeCode] = $billingAddressField;
                }
            }
        }

        return $jsLayout;
    }

    protected function getFieldData($attributeCode, $formName) {
        return [
            'component' => 'Codilar_AdvancedShipping/js/form/element/map',
            'config' => [
                'customScope' => $formName . '.custom_attributes',
                'customEntry' => null,
                'template' => 'Codilar_AdvancedShipping/form/element/map',
                'tooltip' => [
                    'description' => '',
                ],
            ],
            'dataScope' => $formName . '.custom_attributes.' . $attributeCode,
            'label' => '',
            'provider' => 'checkoutProvider',
            'sortOrder' => 0,
            'validation' => [
                'required-entry' => true
            ],
            'visible' => true,
        ];
    }
}