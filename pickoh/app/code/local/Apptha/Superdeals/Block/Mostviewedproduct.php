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
 * @version     1.4
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 * 
 */

/**
 * This file is used to display Most viewed products
 */
class Apptha_Superdeals_Block_Mostviewedproduct extends Mage_Catalog_Block_Product_Abstract {
    
    /**
     * Function to get product limit
     *
     * This Function will return the product count
     * 
     * @return int
     */
    public function getProductsLimit() {
        $counts = Mage::helper ( 'superdeals' )->getMostviewedSidebar ();
        if ($counts) {
            return $counts;
        }
        return 1;
    }
    
    /**
     * Function to get product inital collection
     *
     * This Function will return the product collection
     * 
     * @return array
     */
    public function getProductCollectionInitial() {
        
        /**
         * get store id
         */
        $storeIdData = Mage::app ()->getStore ()->getId ();
        
        /**
         * get reports based on the seller
         */
        $todaysDate = Mage::app ()->getLocale ()->date ()->toString ( Varien_Date::DATETIME_INTERNAL_FORMAT );
        $tomorrow = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) + 1, date ( 'y' ) );
        $dateTomorrow = date ( 'm/d/y', $tomorrow );
        $fromDate = Mage::getModel ( 'core/date' )->date ( 'Y-m-d' );
        $toDate = date ( 'Y-m-d', $tomorrow );
        /**
         * This collection is taked for compatible with magento flat catalog data.
         */
        $_productCollection = Mage::getModel ( 'catalog/product' )->getCollection ()->setDisableFlat ( true )->addAttributeToSelect ( '*' )->addAttributeToFilter ( 'special_price', array (
                'neq' => '' 
        ) )->addAttributeToFilter ( 'special_from_date', array (
                'date' => true,
                'to' => $todaysDate 
        ) )->addAttributeToFilter ( 'special_to_date', array (
                'or' => array (
                        0 => array (
                                'date' => true,
                                'from' => $dateTomorrow 
                        ),
                        1 => array (
                                'is' => new Zend_Db_Expr ( 'null' ) 
                        ) 
                ) 
        ), 'left' )->addAttributeToFilter ( 'status', '1' )->load ();
        
        $productId = array ();
        foreach ( $_productCollection as $proColletion ) {
            $productId [] = $proColletion ['entity_id'];
        }
        /**
         * get reports colletion for Most viewed product
         */
        $products = Mage::getResourceModel ( 'reports/product_collection' )->addAttributeToSelect ( '*' )->setStoreId ( $storeIdData )->addStoreFilter ( $storeIdData )->addViewsCount ( $fromDate, $toDate )->addAttributeToFilter ( "entity_id", array (
                'in' => $productId 
        ) );
        $prefix = Mage::getConfig ()->getTablePrefix ();
        
        
        Mage::getSingleton ( 'catalog/product_status' )->addVisibleFilterToCollection ( $products );
        Mage::getSingleton ( 'catalog/product_visibility' )->addVisibleInCatalogFilterToCollection ( $products );
        
        $products->setPageSize ( 5 )->setCurPage ( 1 );
        
        return $products;
    }
    
    /**
     * Function to get product random collection
     *
     * This Function will return the product collection
     * 
     * @return array
     */
    public function getProductCollectionRandom() {
        $todaysDate = Mage::app ()->getLocale ()->date ()->toString ( Varien_Date::DATETIME_INTERNAL_FORMAT );
        $tomorrow = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) + 1, date ( 'y' ) );
        $dateTomorrow = date ( 'm/d/y', $tomorrow );
        $products = Mage::getModel ( 'catalog/product' )->getCollection ()->addAttributeToFilter ( 'special_price', array (
                'neq' => '' 
        ) )->addAttributeToFilter ( 'special_from_date', array (
                'date' => true,
                'to' => $todaysDate 
        ) )->addAttributeToFilter ( 'special_to_date', array (
                'or' => array (
                        0 => array (
                                'date' => true,
                                'from' => $dateTomorrow 
                        ),
                        1 => array (
                                'is' => new Zend_Db_Expr ( 'null' ) 
                        ) 
                ) 
        ), 'left' );
        
        Mage::getSingleton ( 'catalog/product_status' )->addVisibleFilterToCollection ( $products );
        Mage::getSingleton ( 'catalog/product_visibility' )->addVisibleInCatalogFilterToCollection ( $products );
        
        return $products;
    }
    
    /**
     * Function to get product collection
     *
     * This Function will return the product collection
     * 
     * @return array
     */
    public function getProductCollection() {
        $storeIdData = Mage::app ()->getStore ()->getId ();
        $productCollection = $this->getProductCollectionInitial ();
        $sameProduct = array ();
        $checkedProducts = new Varien_Data_Collection ();
        foreach ( $productCollection as $prod ) {
            
            $parentId = $this->getParentId ( $prod );
            
            if ($parentId == '') {
                continue;
            }
            
            $productData = Mage::getModel ( 'catalog/product' )->setStoreId ( $storeIdData )->load ( $this->getParentId ( $prod ) );
            
            /**
             * if the product is not visible or is disabled
             */
            if (! $productData->isVisibleInCatalog ()) {
                continue;
            }
            
            /**
             * if two or more simple products of the same configurable product are ordered
             */
            if (in_array ( $productData->getId (), $sameProduct )) {
                continue;
            }
            
            $sameProduct [] = $productData->getId ();
            
            if (! $checkedProducts->getItemById ( $parentId )) {
                $checkedProducts->addItem ( $productData );
            }
            
            if (count ( $checkedProducts ) >= $this->getProductsLimit ()) {
                break;
            }
        }
        return $checkedProducts;
    }
    
    /**
     * Function to get product parent id
     *
     * This Function will return the product parent id
     * 
     * @return int
     */
    public function getParentId($products) {
        $parentId = '';
        
        if ($products->getVisibility () != '1') {
            $parentId = $products->getId ();
        } else {
            /**
             * get parent id if the product is not visible
             * this means that the product is associated with a configurable product
             */
            $parentIdArray = $products->loadParentProductIds ()->getData ( 'parent_product_ids' );
            
            if (! empty ( $parentIdArray )) {
                $parentId = $parentIdArray [0];
            }
        }
        return $parentId;
    }
}
