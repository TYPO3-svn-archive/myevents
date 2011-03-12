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
require_once(PATH_t3lib.'class.t3lib_tsparser.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');

require_once(t3lib_extMgm::extPath('myevents').'location/class.tx_myevents_location.php');

/**
 * Plugin 'event' for the 'myevents' extension.
 *
 * @author	 <>
 * @package	TYPO3
 * @subpackage	tx_myevents
 */
class tx_myevents_event extends tslib_pibase {
	var $prefixId      = 'tx_myevents_event';		// Same as class name
	var $scriptRelPath = 'event/class.tx_myevents_event.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'myevents';	// The extension key.
	var $pi_checkCHash = true;
	var $flexIndex    = array();	// Stores the available flexfields; 
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
		//global $GLOBALS;
		//debug(t3lib_extMgm::extPath('myevents'));
		$this->LLkey='de';
		$this->pi_loadLL();	
		$this->pi_initPIflexForm();
		if(!$this->flexIndex)$this->indexFlex();
		//debug($conf);
		$conf=$this->includeFlexInTS($conf);
			
		//debug($conf,'event_conf_received');
		//$conf=$this->resolveConf($conf);
		//if($conf['renderModel.'])$content=$this->cObj->cObjGet($conf['renderModel.']);
		if($conf['renderModel'])$name=$conf['renderModel'];else $name='COA';
		$content=$this->cObj->cObjGetSingle($name,$conf['renderModel.']);
		//debug($content);
		
		return $content;
		
	}
	
	/**
	 * Replaces flexform-fields marked with ²²²field³³³ with flexform-values
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function includeFlexInTS($setup) {
		foreach($setup as $k=>$v){
			if(preg_match("#(\[\[\[)([^\[]+)(\]\]\])#",$v)==1){
				preg_match("#(\[\[\[)([^\[]+)(\]\]\])#",$v,$buffer);
				$pointer=explode('.',trim($buffer[2]));
				if (get_class($this)=='tx_'.trim($pointer[0])){
					$field=trim($pointer[1]);
					$field=$this->processField($field,$setup);
					//debug($field);
					$bufferjoin=$buffer[1].$buffer[2].$buffer[3];
					$v = str_replace($bufferjoin,$field,$v);
					$setup[$k] = str_replace($bufferjoin,$field,$setup[$k]);
				}
			}
			$setup[$k] = $this->includeFlexInTS($setup[$k]);
		}
		//debug($ts,'ts');
		return $setup;
	}
		
	/**
	 * Gets a field of the pi_flexform field
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function indexFlex(){
		foreach($this->cObj->data['pi_flexform']['data'] as $k=>$sheet){
			foreach($sheet['lDEF'] as $field=>$v){
				$this->flexIndex[$field]=$k;
			}
		}
	}
	
	
	function processField($field,$setup,$lang='lDEF') {
		if (!$field) return;
		$fieldname=$field;
		$field=$this->getField($field,$lang);
		
		foreach($setup as $name=>$value){
			if(strstr(trim($name),'myevents')){
				$cN='tx_'.str_replace('.','',$name);
				if($cN!=get_class($this)){
					require_once (t3lib_extMgm::extPath('myevents').'/'.str_replace('.','',$name).'class.'.$cN.'.php');
					if(method_exists(str_replace('.','',$name),$value.ucfirst($fieldname)))$field=call_user_func(array(str_replace('.','',$name), $value.ucfirst($fieldname)), $field,$value);
				}
				elseif(method_exists($this,$name.ucfirst($fieldname)))$field=call_user_func(array($this, $name.ucfirst($fieldname)), $field,$value);
			}
		}
		
		return $field;
	}
	
	function getField($field,$lang='lDEF') {
		if (!$field) return;
		//debug($this->flexIndex);
		if(array_key_exists($field,$this->flexIndex))$field = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $field, $this->flexIndex[$field], $lang);
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
	
	function formatStarttime($field,$format){
		//debug($format);
		if($this->isTimestamp($field)){
			if(!$format)$format='j,(. ),n,(. - ),G,(:),i';
			$formats=explode(',',$format);
			foreach($formats as $k=>$v)$formats[$k]=trim($v);
			foreach($formats as $k=>$v){
				if(strpos($v,'(')===0){$v=str_replace('(','',$v);$v=str_replace(')','',$v);$formats[$k]=$v;}
				else $formats[$k]=date($v,$field);
			}
		}else{
			$field='';
		}
		if($field!='')$field=implode($formats);
		return $field;
	}
	
	function formatStarttimestamp($field,$format){
		$field = $this->formatStarttime($field,$format);
		return $field;
	}
	
	function getStarttimestamp(){
		$field=$this->getField('starttime');
		return $field;
	}
	
	function formatEndtime($field,$format){
		$field = $this->formatStarttime($field,$format);
		return $field;
	}
	
	function formatDuration($field,$format){
		$starttime=$this->getField('starttime');
		$endtime=$this->getField('endtime');
		if(!$starttime or !$endtime or !$this->isTimestamp($starttime) or !$this->isTimestamp($endtime))return ;
		$duration=$endtime-$starttime;
		if(($duration/60/60)>24)$duration=round($duration/60/60/24).' '.$this->pi_getLL('days');
		else $duration=round($duration/60/60).' '.$this->pi_getLL('hours');	
		return $duration;
	}
	
	function formatTeaser($field,$format){
		return $field;
	}
	
	function getCategory(){
		$field=$this->field('category','options');
		return $field;
		foreach($fields['category']as$k=>$v){
			$query='SELECT name FROM tx_myevents_categories WHERE uid="'.$v.'"';
			$res=mysql_query($query);
			while($row=mysql_fetch_row($res)){$fields['category'][$v]=$row;}
		}
	}
	
	function getDiscipline(){
		$field=$this->field('discipline','options');
		return $field;
	}
	
	function getEnablereg(){
		$field=$this->field('enablereg','registration');
		return $field;
	}
	
	function formatRegstart($field,$format){
		$field = $this->formatStarttime($field,$format);
		return $field;
	}
	
	function getFee(){
		$field=$this->field('fee','registration');
		return $field;
	}
	
	function getMaxattendees(){
		$field=$this->field('maxattendees','registration');
		return $field;
	}
	
	function getHeader(){
		$field=$this->cObj->data['header'];
		return $field;
	}
	
	function isTimestamp( $string ) {
		if ( 1 === preg_match( '~^[1-9][0-9]*$~', $string ) && strlen($string)>9)return true;
		else return false;
	} 
		
	function isCategory( $categories ) {
		if(!$categories)return true;
		$event_categories=$this->getField('category');
		$event_categories=explode(',',$event_categories);
		foreach($event_categories as $k=>$v)$event_categories[$k]=trim($v);
		foreach($categories as $category){
			foreach($event_categories as $event_category){
				if($category==$event_category) return true;
			}
			
		}
		return false;
	} 
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/event/class.tx_myevents_event.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/event/class.tx_myevents_event.php']);
}

?>