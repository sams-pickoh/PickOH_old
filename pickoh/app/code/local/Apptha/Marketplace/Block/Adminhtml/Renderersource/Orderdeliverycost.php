<?php

/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Marketplace
 * @version     1.9.1
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2016 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 */

/**
 * Renderer to display Delivery cost details
 */
class Apptha_Marketplace_Block_Adminhtml_Renderersource_Orderdeliverycost extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    /**
     * Function to display Delivery cost details
     * 
     * Return the customer details
     * @return integer
     */
    public function render(Varien_Object $row) {
        $value = $row->getData($this->getColumn()->getIndex());
        /**
        * Get the sales order collection by increment id
         */
        $order = Mage::getModel('sales/order')->loadByIncrementId($value); 
        return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().$order->getShippingDeliveryCost();        
    }

}

