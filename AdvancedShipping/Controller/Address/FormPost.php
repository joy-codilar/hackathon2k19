<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdvancedShipping\Controller\Address;


class FormPost extends \Magento\Customer\Controller\Address\FormPost
{
    protected function _extractAddress()
    {
        $address = parent::_extractAddress();
        $address->setCustomAttribute('lat_lng', $this->getRequest()->getParam('lat_lng'));
        return $address;
    }
}