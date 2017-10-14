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
 * 
 */
/**
 * This class initiates primary key for tranasction model
 */
class Apptha_Marketplace_Model_Mysql4_Transaction extends Mage_Core_Model_Mysql4_Abstract{
/**
 * primay key:id
 * {@inheritDoc}
 * @see Mage_Core_Model_Resource_Abstract::_construct()
 */
    public function _construct(){    
        /**
         *  Note that the id refers to the key field in your database table.
         */
        $this->_init('marketplace/transaction', 'id');
    }
} 