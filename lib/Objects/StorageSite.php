<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of Onlogistics, a web based ERP and supply chain 
 * management application. 
 *
 * Copyright (C) 2003-2008 ATEOR
 *
 * This program is free software: you can redistribute it and/or modify it 
 * under the terms of the GNU Affero General Public License as published by 
 * the Free Software Foundation, either version 3 of the License, or (at your 
 * option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT 
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public 
 * License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.1.0+
 *
 * @package   Onlogistics
 * @author    ATEOR dev team <dev@ateor.com>
 * @copyright 2003-2008 ATEOR <contact@ateor.com> 
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU AGPL
 * @version   SVN: $Id$
 * @link      http://www.onlogistics.org
 * @link      http://onlogistics.googlecode.com
 * @since     File available since release 0.1.0
 * @filesource
 */

class StorageSite extends _StorageSite {
    // Constructeur {{{

    /**
     * StorageSite::__construct()
     * Constructeur
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    // }}}
    // StorageSite::getLocationCollection() {{{

	/**
     * Methode AddOn pour recuperer les Location pour ce site
     * @return LocationCollection Content of value
     * @access public
     */
    function getLocationCollection(){

        $SiteLocationCollection = new Collection();
        $StoreCollection = $this->getStoreCollection();
        if(0 == count($StoreCollection)){
            return new Collection();
            }
        for($i = 0; $i < $StoreCollection->getCount(); $i++){
            $item = $StoreCollection->getItem($i);
            $LocationCollection = $item->getLocationCollection();
            if(0 == count($LocationCollection)){
                continue;
                }
            for($j = 0; $j < $LocationCollection->getCount(); $j++){
                $Location = $LocationCollection->getItem($j);
                $SiteLocationCollection->setItem($Location);
                unset($Location);
                }
            unset($item);
            }
        return $SiteLocationCollection;
    }

    // }}}
    // StorageSite::getProductCollection() {{{

     /**
     * Methode AddOn pour recuperer les Product pour ce site
     * @return ProductCollection Content of value
     * @access public
     */
    function getProductCollection(){

        $ProductCollection = new Collection();
        $LocationCollection = $this->getLocationCollection();
        if(0 == count($LocationCollection)){
            return new Collection();
            }
        for($i = 0; $i < $LocationCollection->getCount(); $i++){
            $item = $LocationCollection->getItem($i);
            $LocationProductQuantitiesCollection = $item->getLocationProductQuantitiesCollection();
            if(0 == count($LocationProductQuantitiesCollection)){
                continue;
                }
            for($j = 0; $j < $LocationProductQuantitiesCollection->getCount(); $j++){
                $LocationProductQuantities = $LocationProductQuantitiesCollection->getItem($j);
                $Product = $LocationProductQuantities->getProduct();
                $ProductCollection->setItem($Product);
                unset($Product);
                }
            unset($item);
            }
        return $ProductCollection;
    }

    // }}}
    // StorageSite::contendStoreWithStockOwner() {{{

   /**
     * Retourne true si contient un Store ayant pour StockOwner l'Actor passe en param
     * @param $ActorId :integer: Id d'Actor
     */
    function contendStoreWithStockOwner($ActorId) {
        $StoreMapper = Mapper::singleton("Store");
        $StoreCollection = $StoreMapper->loadCollection(array("StorageSite" => $this->getId(), "StockOwner.Id" => $ActorId));
        return (!Tools::isEmptyObject($StoreCollection));
    }

    // }}}
    // StorageSite::getStoreNameArray() {{{

    /**
     * Retourne un tableau contenant le nom des Store qu'il contient
     * @access public
     * @return array of strings
     **/
    function getStoreNameArray(){
        $NameArray = array();
        $StoreCollection = $this->getStoreCollection();
        for ($i=0;$i<$StoreCollection->getCount();$i++) {
            $Store = $StoreCollection->getItem($i);
            $NameArray[] = $Store->getName();
            unset($Store);
        }
        return $NameArray;
    }

    // }}}
    // StorageSite::getStockOwnerCollection() {{{

    /**
     * Retourne la collection des StockOwner des Store qu'il contient
     * @access public
     * @return object Collection
     **/
    function getStockOwnerCollection() {
        $coll = new Collection();
        $coll->acceptDuplicate = false;
        $StoreCollection = $this->getStoreCollection();
        $count = $StoreCollection->getCount();
        for ($i = 0; $i < $count; $i++) {
            $Store = $StoreCollection->getItem($i);
            $coll->setItem($Store->getStockOwner());
            unset($Store);
        }
        return $coll;
    }

    // }}}
    // StorageSite::isDeletable() {{{

    /**
     * Retourne true si le StorageSite est supprimable, et false sinon
     * @access public
     * @return boolean
     **/
    function isDeletable() {
         $storeColl = $this->getStoreCollection();
        // si la collection est vide, on peut supprimer le site sans probleme
        if (Tools::isEmptyObject($storeColl)) {
            return true;
        }
        $count = $storeColl->getCount();
        for($i = 0; $i < $count; $i++) {
            $store = $storeColl->getItem($i);
            if (!$store->isDeletable()) {
                return false;
            }
        }
        return true;
    }

    // }}}
    // StorageSite::delete() {{{

    /**
     * Supprime un site de stockage.
     * Appelle isDeletable pour la vérification.
     *
     * @access public
     * @return boolean
     * @throws Exception
     */
    function delete($fake = false) {
        if (!$this->isDeletable()) {
            throw new Exception(sprintf(
                _('Storage site "%s" cannot be deleted.'),
                $this->getName()
            ));
        }
        if ($fake) {
            return true;
        }
        return parent::delete();
    }

    // }}}

}

?>