<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE') {
	//t3lib_extMgm::addModulePath('txmyeventsM1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
	//t3lib_extMgm::addModule('txmyeventsM1', '', 'top', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
	
}


t3lib_extMgm::allowTableOnStandardPages('tx_myevents_locations');
t3lib_extMgm::addToInsertRecords('tx_myevents_locations');

t3lib_extMgm::allowTableOnStandardPages('tx_myevents_categories');
t3lib_extMgm::addToInsertRecords('tx_myevents_categories');


$TCA['tx_myevents_locations'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_myevents_referents.gif',
	),
);

$TCA['tx_myevents_categories'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_categories',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_myevents_categories.gif',
	),
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types'][$_EXTKEY . '_referent']['showitem'] = 'CType;;;button;1-1-1,hidden, '.
'header;LLL:EXT:myevents/locallang_db.xml:tx_myevents_referent.name;;;2-2-2,'.
'pi_flexform;LLL:EXT:myevents/locallang_db.xml:tx_myevents_referents.information;;;1-1-1,'.
'image;LLL:EXT:myevents/locallang_db.xml:tx_myevents_referent.portrait;;;2-2-2,'.
'bodytext;LLL:EXT:myevents/locallang_db.xml:tx_myevents_referent.biography;;richtext:rte_transform[flag=rte_enabled|mode=ts_css];3-3-3,';
$TCA['tt_content']['columns']['pi_flexform']['config']['ds'][','.$_EXTKEY.'_referent'] = 'FILE:EXT:'.$_EXTKEY.'/flexform_ds_referent.xml';
$TCA['tt_content']['types'][$_EXTKEY . '_referent']['subtypes_addlist'][$_EXTKEY . '_referent'] = 'referent_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_referent', 'FILE:EXT:flexform_ds_referent.xml');

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:myevents/locallang_db.xml:tt_content.CType_referent',
	$_EXTKEY . '_referent',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'CType');

$TCA['tt_content']['types'][$_EXTKEY . '_event']['showitem'] = 'CType;;;button;1-1-1,hidden, '.
'header;LLL:EXT:myevents/locallang_db.xml:tx_myevents_event.name;;;2-2-2,'.
'bodytext;LLL:EXT:myevents/locallang_db.xml:tx_myevents_event.description;;richtext:rte_transform[flag=rte_enabled|mode=ts_css];3-3-3,'.
'--div--;LLL:EXT:myevents/locallang_db.xml:tt_content.CType_event;;;,'.
'pi_flexform;LLL:EXT:myevents/locallang_db.xml:tt_content.CType_event;;;1-1-1,'.
'--div--;LLL:EXT:myevents/locallang_db.xml:tt_content.event.images;;;,'.
'image;LLL:EXT:myevents/locallang_db.xml:tt_content.event.images;;;4-4-4,'.
'imagecaption;;, '.
'imagecols;;,';
$TCA['tt_content']['columns']['pi_flexform']['config']['ds'][','.$_EXTKEY.'_event'] = 'FILE:EXT:'.$_EXTKEY.'/flexform_ds_event.xml';
$TCA['tt_content']['types'][$_EXTKEY . '_event']['subtypes_addlist'][$_EXTKEY . '_event'] = 'event_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_event', 'FILE:EXT:flexform_ds_event.xml');

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:myevents/locallang_db.xml:tt_content.CType_event',
	$_EXTKEY . '_event',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'CType');

/*
$TCA['tt_content']['types'][$_EXTKEY . '_programm']['showitem'] = 'CType;;;button;1-1-1,hidden, '.
'header;LLL:EXT:myevents/locallang_db.xml:tx_myevents_programm.name;;;2-2-2,'.
'bodytext;LLL:EXT:myevents/locallang_db.xml:tx_myevents_event.description;;richtext:rte_transform[flag=rte_enabled|mode=ts_css];3-3-3,'.
'--div--;LLL:EXT:myevents/locallang_db.xml:tt_content.CType_programm;;;,'.
'pi_flexform;LLL:EXT:myevents/locallang_db.xml:tt_content.CType_programm;;;1-1-1,';
$TCA['tt_content']['columns']['pi_flexform']['config']['ds'][','.$_EXTKEY.'_programm'] = 'FILE:EXT:'.$_EXTKEY.'/flexform_ds_programm.xml';
$TCA['tt_content']['types'][$_EXTKEY . '_programm']['subtypes_addlist'][$_EXTKEY . '_programm'] = 'programm_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_programm', 'FILE:EXT:flexform_ds_programm.xml');

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:myevents/locallang_db.xml:tt_content.CType_programm',
	$_EXTKEY . '_programm',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'CType');
*/

?>