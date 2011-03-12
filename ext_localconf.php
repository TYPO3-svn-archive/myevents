<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_myevents_referents=1
');

$tt_content = file_get_contents(t3lib_extMgm::extPath($_EXTKEY).'/ext_typoscript_content.txt');

$cN = 'tx_myevents_location';

t3lib_extMgm::addTypoScript($_EXTKEY, 'setup', '# Setting '.$_EXTKEY.' plugin TypoScript 
	plugin.'.$cN.' = USER
	plugin.'.$cN.' {
  		includeLibs = '.t3lib_extMgm::siteRelPath($_EXTKEY).'location/class.tx_myevents_location.php
  		userFunc = '.$cN.'->main
	}', 43);

t3lib_extMgm::addPItoST43($_EXTKEY, 'referent/class.tx_myevents_referent.php', '_referent', 'CType', 1);
t3lib_extMgm::addTypoScript($_EXTKEY,"setup","tt_content.shortcut.20.0.conf.tx_myevents_referent = < plugin.".t3lib_extMgm::getCN($_EXTKEY)."_referent",43);
t3lib_extMgm::addPItoST43($_EXTKEY, 'event/class.tx_myevents_event.php', '_event', 'CType', 1);
t3lib_extMgm::addTypoScript($_EXTKEY,"setup","tt_content.shortcut.20.0.conf.tx_myevents_event = < plugin.".t3lib_extMgm::getCN($_EXTKEY)."_event",43);
//t3lib_extMgm::addPItoST43($_EXTKEY, 'programm/class.tx_myevents_programm.php', '_programm', 'CType', 1);
//t3lib_extMgm::addTypoScript($_EXTKEY,"setup","tt_content.shortcut.20.0.conf.tx_myevents_programm = < plugin.".t3lib_extMgm::getCN($_EXTKEY)."_programm",43);
t3lib_extMgm::addTypoScript($_EXTKEY, 'setup', '# Setting '.$_EXTKEY.' plugin TypoScript '.$tt_content, 43);


?>