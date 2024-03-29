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

class _Currency extends Object {
    
    // Constructeur {{{

    /**
     * _Currency::__construct()
     * Constructeur
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    // }}}
    // Name i18n_string property + getter/setter {{{

    /**
     * Name foreignkey
     *
     * @access private
     * @var mixed object I18nString or integer
     */
    private $_Name = 0;

    /**
     * _Currency::getName
     *
     * @access public
     * @param string $locale optional, default is the current locale code
     * @param boolean $useDefaultLocaleIfEmpty determine if the getter must
     * return the translation in the DEFAULT_LOCALE if no translation is found
     * in the current locale.
     * @return string
     */
    public function getName($locale=false, $defaultLocaleIfEmpty=true) {
        $locale = $locale !== false ? $locale : I18N::getLocaleCode();
        if (is_int($this->_Name) && $this->_Name > 0) {
            $this->_Name = Object::load('I18nString', $this->_Name);
        }
        $ret = null;
        if ($this->_Name instanceof I18nString) {
            $getter = 'getStringValue_' . $locale;
            $ret = $this->_Name->$getter();
            if ($ret == null && $defaultLocaleIfEmpty) {
                $getter = 'getStringValue_' . LOCALE_DEFAULT;
                $ret = $this->_Name->$getter();
            }
        }
        return $ret;
    }

    /**
     * _Currency::getNameId
     *
     * @access public
     * @return integer
     */
    public function getNameId() {
        if ($this->_Name instanceof I18nString) {
            return $this->_Name->getId();
        }
        return (int)$this->_Name;
    }

    /**
     * _Currency::setName
     *
     * @access public
     * @param string $value
     * @param string $locale optional, default is the current locale code
     * @return void
     */
    public function setName($value, $locale=false) {
        if (is_numeric($value)) {
            $this->_Name = (int)$value;
        } else if ($value instanceof I18nString) {
            $this->_Name = $value;
        } else {
            $locale = $locale !== false ? $locale : I18N::getLocaleCode();
            if (!($this->_Name instanceof I18nString)) {
                $this->_Name = Object::load('I18nString', $this->_Name);
                if (!($this->_Name instanceof I18nString)) {
                    $this->_Name = new I18nString();
                }
            }
            $setter = 'setStringValue_'.$locale;
            $this->_Name->$setter($value);
            $this->_Name->save();
        }
    }

    // }}}
    // ShortName string property + getter/setter {{{

    /**
     * ShortName string property
     *
     * @access private
     * @var string
     */
    private $_ShortName = '';

    /**
     * _Currency::getShortName
     *
     * @access public
     * @return string
     */
    public function getShortName() {
        return $this->_ShortName;
    }

    /**
     * _Currency::setShortName
     *
     * @access public
     * @param string $value
     * @return void
     */
    public function setShortName($value) {
        $this->_ShortName = $value;
    }

    // }}}
    // Symbol string property + getter/setter {{{

    /**
     * Symbol string property
     *
     * @access private
     * @var string
     */
    private $_Symbol = '';

    /**
     * _Currency::getSymbol
     *
     * @access public
     * @return string
     */
    public function getSymbol() {
        return $this->_Symbol;
    }

    /**
     * _Currency::setSymbol
     *
     * @access public
     * @param string $value
     * @return void
     */
    public function setSymbol($value) {
        $this->_Symbol = $value;
    }

    // }}}
    // getTableName() {{{

    /**
     * Retourne le nom de la table sql correspondante
     *
     * @static
     * @access public
     * @return string
     */
    public static function getTableName() {
        return 'Currency';
    }

    // }}}
    // getObjectLabel() {{{

    /**
     * Retourne le "label" de la classe.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getObjectLabel() {
        return _('None');
    }

    // }}}
    // getProperties() {{{

    /**
     * Retourne le tableau des propri�t�s.
     * Voir Object pour documentation.
     *
     * @static
     * @access public
     * @return array
     * @see Object.php
     */
    public static function getProperties() {
        $return = array(
            'Name' => Object::TYPE_I18N_STRING,
            'ShortName' => Object::TYPE_STRING,
            'Symbol' => Object::TYPE_STRING);
        return $return;
    }

    // }}}
    // getLinks() {{{

    /**
     * Retourne le tableau des entit�s li�es.
     * Voir Object pour documentation.
     *
     * @static
     * @access public
     * @return array
     * @see Object.php
     */
    public static function getLinks() {
        $return = array(
            'AbstractDocument'=>array(
                'linkClass'     => 'AbstractDocument',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'Account'=>array(
                'linkClass'     => 'Account',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'Actor'=>array(
                'linkClass'     => 'Actor',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'ActorBankDetail'=>array(
                'linkClass'     => 'ActorBankDetail',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'Command'=>array(
                'linkClass'     => 'Command',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'CurrencyConverter'=>array(
                'linkClass'     => 'CurrencyConverter',
                'field'         => 'FromCurrency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'CurrencyConverter_1'=>array(
                'linkClass'     => 'CurrencyConverter',
                'field'         => 'ToCurrency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'Flow'=>array(
                'linkClass'     => 'Flow',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'ForecastFlow'=>array(
                'linkClass'     => 'ForecastFlow',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'HandingByRange'=>array(
                'linkClass'     => 'HandingByRange',
                'field'         => 'Currency',
                'ondelete'      => 'cascade',
                'multiplicity'  => 'onetomany'
            ),
            'MiniAmountToOrder'=>array(
                'linkClass'     => 'MiniAmountToOrder',
                'field'         => 'Currency',
                'ondelete'      => 'cascade',
                'multiplicity'  => 'onetomany'
            ),
            'PriceByCurrency'=>array(
                'linkClass'     => 'PriceByCurrency',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'ProductHandingByCategory'=>array(
                'linkClass'     => 'ProductHandingByCategory',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ),
            'Promotion'=>array(
                'linkClass'     => 'Promotion',
                'field'         => 'Currency',
                'ondelete'      => 'nullify',
                'multiplicity'  => 'onetomany'
            ));
        return $return;
    }

    // }}}
    // getUniqueProperties() {{{

    /**
     * Retourne le tableau des propri�t�s qui ne peuvent prendre la m�me valeur
     * pour 2 occurrences.
     *
     * @static
     * @access public
     * @return array
     */
    public static function getUniqueProperties() {
        $return = array();
        return $return;
    }

    // }}}
    // getEmptyForDeleteProperties() {{{

    /**
     * Retourne le tableau des propri�t�s doivent �tre "vides" (0 ou '') pour
     * qu'une occurrence puisse �tre supprim�e en base de donn�es.
     *
     * @static
     * @access public
     * @return array
     */
    public static function getEmptyForDeleteProperties() {
        $return = array();
        return $return;
    }

    // }}}
    // getFeatures() {{{

    /**
     * Retourne le tableau des "fonctionalit�s" pour l'objet en cours.
     * Voir Object pour documentation.
     *
     * @static
     * @access public
     * @return array
     * @see Object.php
     */
    public static function getFeatures() {
        return array();
    }

    // }}}
    // getMapping() {{{

    /**
     * Retourne le mapping n�cessaires aux composants g�n�riques.
     * Voir Object pour documentation.
     *
     * @static
     * @access public
     * @return array
     * @see Object.php
     */
    public static function getMapping() {
        $return = array();
        return $return;
    }

    // }}}
}

?>