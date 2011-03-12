<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}



$TCA['tx_myevents_locations'] = array (
	'ctrl' => $TCA['tx_myevents_locations']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,title,street,city,zip,building,room'
	),
	'feInterface' => $TCA['tx_myevents_locations']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_myevents_referents',
				'foreign_table_where' => 'AND tx_myevents_referents.pid=###CURRENT_PID### AND tx_myevents_referents.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'street' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.street',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim,required',
			)
		),
		'city' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.city',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim',
			)
		),
		'zip' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.zip',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim',
			)
		),
		'building' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.building',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim',
			)
		),
		'room' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.room',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim',
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title,building, room, street, zip, city')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);

$TCA['tx_myevents_categories'] = array (
	'ctrl' => $TCA['tx_myevents_categories']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,name'
	),
	'feInterface' => $TCA['tx_myevents_categories']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_myevents_referents',
				'foreign_table_where' => 'AND tx_myevents_referents.pid=###CURRENT_PID### AND tx_myevents_referents.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:myevents/locallang_db.xml:tx_myevents_locations.street',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval'     => 'trim',
			)
		)
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, name')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>