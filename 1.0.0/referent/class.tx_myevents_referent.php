<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011  <>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Referent' for the 'myevents' extension.
 *
 * @author	 <>
 * @package	TYPO3
 * @subpackage	tx_myevents
 */
class tx_myevents_referent extends tslib_pibase {
	var $prefixId      = 'tx_myevents_referent';		// Same as class name
	var $scriptRelPath = 'referent/class.tx_myevents_referent.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'myevents';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
		return 'Hello World!<HR>
			Here is the TypoScript passed to the method:'.
					t3lib_div::view_array($conf);
	}
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function echoPosition($content, $conf)	{
		return 'Hello World!myfreind'.
					t3lib_div::view_array($conf);
					//t3lib_div::view_array($content);
	}

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function fields($content, $conf ) {
	
		$this->pi_loadLL();
		$this->pi_setPiVarDefaults();
		$this->pi_initPIflexForm();
							
		$tv_field = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $conf['flex_data'], $conf['sheet_pointer'], $conf['lang'], $conf['value_def']);
		
		switch ($conf['flex_data']){
		case "company":
			$tv_field=explode(" ",$tv_field);
			//$content=t3lib_div::view_array($tv_field);
			$mod_field="<a";
			if($tv_field[1]!="-"&&$tv_field[1]){$mod_field.=" target='".$tv_field[1]."' ";}
			if($tv_field[2]!="-"&&$tv_field[2]){$mod_field.=" class='".$tv_field[2]."' ";}
			if($tv_field[0]!="-"&&$tv_field[0]){$mod_field.=" href='http://".$tv_field[0]."' ";}
			if($tv_field[3]!="-"&&$tv_field[3]){$mod_field.=" >".$tv_field[3];}else{$mod_field.=" >".$tv_field[0];}
			unset($tv_field);
			$tv_field.=$mod_field."</a>";
		break;
		case "email":
			$tv_field="<a href='mailto:".$tv_field."'>".$tv_field."</a>";
		break;
		}
		return $tv_field;

	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/referent/class.tx_myevents_referent.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/referent/class.tx_myevents_referent.php']);
}

?>