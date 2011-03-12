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
require_once(PATH_tslib.'class.tslib_content.php');
require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'location' for the 'myevents' extension.
 *
 * @author	 <>
 * @package	TYPO3
 * @subpackage	tx_myevents
 */
class tx_myevents_location extends tslib_pibase {
	var $prefixId      = 'tx_myevents_location';		// Same as class name
	var $scriptRelPath = 'location/class.tx_myevents_location.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'myevents';	// The extension key.
	var $pi_checkCHash = true;
	var $flexfields    = array();	// Stores the available flexfields; 
		
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
		
		$this->LLkey='de';
		$this->pi_loadLL();	
		
		$xml_ds=file_get_contents(t3lib_extMgm::extPath('myevents').'flexform_ds_event.xml');
		$flexfields=(t3lib_div::xml2array($xml_ds));
		foreach($flexfields['sheets'] as $k=>$sheet){
			foreach($sheet['ROOT']['el'] as $field=>$v){
				$this->flexfields[$field]=$k;
			}
		}
		$cObj=$this->cObj->data;		
		$this->cObj->data=$this->pi_getRecord('tx_myevents_locations',$this->getField('location'));
		unset($this->flexfields);
		//return t3lib_div::view_array($cObj);
		
		$conf=$this->fillTsWithFields($conf);
		//debug($conf,'ts');
		
		$content=$this->cObj->cObjGetSingle('COA',$conf['renderModel.']);
		//debug($content);
		$this->cObj->data=$cObj;
		return $content;
						
		$return();
		
		

		return $content;
		
	}
	
	/**
	 * Gets a field of the pi_flexform field
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function field($field,$sheet='sDEF',$lang='lDEF') {
			
		if (!$field) return;
		$field = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $field, $sheet, $lang);
		return $field;

	}
	
	function fillTsWithFields($ts) {
		foreach($ts as $k=>$v){
			while(preg_match("#(\[\[\[)([^\[]+)(\]\]\])#",$v)==1){
				preg_match("#(\[\[\[)([^\[]+)(\]\]\])#",$v,$buffer);
				$field=$this->renderField(trim($buffer[2]),$format=$ts['format.']);
				//debug($field,'field');
				$bufferjoin=$buffer[1].$buffer[2].$buffer[3];
				$v = str_replace($bufferjoin,$field,$v);
				$ts[$k] = str_replace($bufferjoin,$field,$ts[$k]);
			}
			$ts[$k] = $this->fillTsWithFields($ts[$k]);
		}
		//debug($ts,'ts');
		return $ts;
	}
	
	/**
	 * Gets a field of the pi_flexform field
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function renderField($field,$format,$lang='lDEF') {
		if (!$field) return;
		$fieldname=$field;
		$field=$this->getField($field);
		if(method_exists($this,'format'.ucfirst($fieldname)))$field=call_user_func(array($this, 'format'.ucfirst($fieldname)), $field,$format);
		//debug($format,$field);
		if($format['makeGoogleLink']==1){
			$fields['street']=$this->getField('street');$fields['zip']=$this->getField('zip');$fields['city']=$this->getField('city');
			$googlelink='href="http://maps.google.de/maps?&q='.$fields['street'].'+'.$lfields['zip'].'+'.$fields['city'].'"';
			if($format['makeGoogleLink.']['target'])$target='target="'.$format['makeGoogleLink.']['target'].'"';
			$field=$this->cObj->wrap($field,'<a '.$target.' '.$googlelink.'>|</a>');
		}
		return $field;
	}
	
	function getField($field,$lang='lDEF') {
		if (!$field) return;
		if(array_key_exists($field,$this->flexfields))$field = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $field, $this->flexfields[$field], $lang);
		elseif(array_key_exists($field,$this->cObj->data))$field=$this->cObj->data[$field];
		else $field='';		
		return $field;
	}
	
	function user_func_exists($function_name = '') {
	    $func = get_defined_functions();
	    $user_func = array_flip($func['user']);
	    unset($func);
	    return ( isset($user_func[$function_name]) );  
	}
	
	function getTitle($cObj){
		$field=$cObj['title'];
		return $field;
	}
	
	function getBuilding($cObj){
		$field=$cObj['building'];
		return $field;
	}
	
	function getRoom($cObj){
		$field=$cObj['room'];
		return $field;
	}
	
	function getStreet($cObj){
		$field=$cObj['street'];
		return $field;
	}
	
	function getCity($cObj){
		$field=$cObj['city'];
		return $field;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/location/class.tx_myevents_location.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/location/class.tx_myevents_location.php']);
}

?>