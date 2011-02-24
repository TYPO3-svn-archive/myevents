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
require_once(t3lib_extMgm::extPath('myevents').'event/class.tx_myevents_event.php');
require_once(t3lib_extMgm::extPath('myevents').'location/class.tx_myevents_location.php');


/**
 * Plugin 'programm' for the 'myevents' extension.
 *
 * @author	 <>
 * @package	TYPO3
 * @subpackage	tx_myevents
 */
class tx_myevents_programm extends tslib_pibase {
	var $prefixId      = 'tx_myevents_programm';		// Same as class name
	var $scriptRelPath = 'programm/class.tx_myevents_programm.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'myevents';	// The extension key.
	var $pi_checkCHash = true;
	var $events = array();
	var $flexIndex    = array();	// Stores the available flexfields;
	var $tsParser;
	var $processConfDeth=0;
	var $groupTemplate;
	var $groups = array();
		
	
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
		global $GLOBALS;
		
		$this->pi_initPIflexForm();
		$this->flexIndex=$this->indexFlex();
		$this->LLkey='de';
		$this->pi_loadLL();	
		
		$conf = $this->processConf($conf);
		$conf = $this->includeFlexInTS($conf);
		//debug($conf,'conf',$a,$b,5,5);
		
		$query ='SELECT * FROM tt_content WHERE CType="myevents_event" AND deleted="0" AND hidden="0"';
		$res=mysql_query($query);
		while($assoc=mysql_fetch_assoc($res)){
			$event_buffer=t3lib_div::makeInstance('tx_myevents_event');
			$event_buffer->cObj = t3lib_div::makeInstance('tslib_cObj');
			$event_buffer->cObj->start($assoc,'tt_content');
			$event_buffer->indexFlex();
			if($event_buffer->isCategory($categories)) 
			$this->events[$event_buffer->cObj->data['uid']]=$event_buffer;
		}
		
		//debug($this->events);
		
		if($conf['sortBy']=='date'){
			$sortedevents=array();
			foreach($this->events as $event) $eventsbydate[$event->cObj->data['uid']]=$event->getStarttimestamp();
			
			asort($eventsbydate,3);
			//return t3lib_div::view_array($eventsbydate);
			foreach($eventsbydate as $key=>$eventbydate){
				foreach($this->events as $event){
					if ($event->cObj->data['uid']==$key)array_push($sortedevents,$event);
					//debug($event->cObj->data['uid']);
					//debug($ventsbydate);
				}
			}
			$this->events=$sortedevents;
		}
		
		if($conf['groupTemplate']){
			//$this->groupTemplate=t3lib_div::makeInstance('tslib_cObj');
			//$this->groupTemplate->data=$conf['groupTemplate'];
			foreach($this->events as $event){
				
			}
			
		}
		
		
		if($conf['eventTemplate.'])$eventConf['renderModel.']=$conf['eventTemplate.'];
		if($conf['eventTemplate'])$eventConf['renderModel']=$conf['eventTemplate'];
		foreach ($this->events as $event) $content.=$event->main('',$eventConf);
		
		//debug($content,'result');
		
		return $content;
		//$content.=$event->main('',$conf['renderModel']);
		if($conf['wrap']&&$content)$content= $this->cObj->wrap($content,$conf['wrap']);
		if($conf['stdwrap']&&$content)$content=$this->cObj->stdWrap($content,$conf['stdWrap']);
		//return debug($this->cObj);
		
		return $content;
		
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
	
	function sortEvents($sortBy){
		if(!$sortBy)return;
		//switch ($sortBy)
	}
	
	function processConf($confIn){
		foreach($confIn as $k=> $conf){
			if(is_string($conf)){
				$confIn=$this->mergeTSRef($confIn,$k);
			}
			if($confIn[$k.'.'])$confIn[$k.'.']=$this->processConf($confIn[$k.'.']);
			if(is_array($conf))$confIn[$k]=$this->processConf($confIn[$k]);
		}
			return $confIn;
	}
	
	function mergeTSRef($confArr,$prop)	{
		if (substr($confArr[$prop],0,1)=='<')	{
			$key = trim(substr($confArr[$prop],1));
			$cF = t3lib_div::makeInstance('t3lib_TSparser');
			// $name and $conf is loaded with the referenced values.
			$old_conf=$confArr[$prop.'.'];
			list($name, $conf) = $cF->getVal($key,$GLOBALS['TSFE']->tmpl->setup);
			while(substr(trim($name),0,1)=='<'){
				$key = trim(substr($name,1));
				// $name and $conf is loaded with the referenced values.
				list($name, $conf) = $cF->getVal($key,$GLOBALS['TSFE']->tmpl->setup);
			}
			if (is_array($old_conf) && count($old_conf))	{
				$conf = $this->cObj->joinTSarrays($conf,$old_conf);
			}
			if($name)$confArr[$prop]=$name;
			if($conf)$confArr[$prop.'.']=$conf;
		}
		return $confArr;
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
	
	function getCategories(){
		$field=$this->field('categories');
		if($field)$field=explode(',',$field);
		foreach($field as $k=>$v)$field[$k]=trim($v);
		return $field;
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

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/programm/class.tx_myevents_programm.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/myevents/programm/class.tx_myevents_programm.php']);
}

?>