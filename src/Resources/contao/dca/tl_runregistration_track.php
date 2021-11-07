<?php
/*
 * Contao Runregistration bundle.
 *
 * (c) 2016-2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

$GLOBALS['TL_DCA']['tl_runregistration_track'] = [
	'config' => [
		'dataContainer' => 'Table',
		'ptable' => 'tl_runregistration_run',
		'ctable' => array('tl_runregistration_attendee'),
		'doNotDeleteRecords' => false,
		'notCopyable' => true,
		'switchToEdit' => false,
		'enableVersioning' => false,
		'sql' => [
			'keys' => [
				'id' => 'primary',
			]
		]
	],
	'list' => [
		'sorting' => [
			'mode' => 2,
			'fields' => ['start_time', 'name'],
			'flag' => 11,
			'panelLayout' => 'sort',
		],
		'label' => [
			'fields' => ['name', 'start_time'],
			'showColumns' => true,
			'format' => '%s',
			'label_callback' => array('tl_runregistration_track', 'formatLabel'),
		],
		'global_operations' => [],
		'operations' => [
			'exportcsv' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['exportcsv'],
				'href' => 'key=exportcsv',
				'icon' => '/bundles/contaorunregistration/table_go.png',
			],
			'editattendee' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['editattendee'],
				'href' => 'table=tl_runregistration_attendee',
				'icon' => '/bundles/contaorunregistration/group.png',
			],
			'edit' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['edit'],
				'href' => 'act=edit',
				'icon' => 'edit.svg',
			],
			'delete' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['delete'],
				'href' => 'act=delete',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'icon' => 'delete.svg',
			],
		],
	],
	'palettes' => [
		'__selector__' => [],
		'default' => '{title_general},name,start_time,online_registration_enabled',
	],
	'subpalettes' => [
		'' => '',
	],
	'fields' => [
		'id' => [
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		],
		'pid' => array
		(
			'foreignKey' => 'tl_runregistration_run.id',
			'sql' => "int(10) unsigned NOT NULL default '0'",
			'relation' => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => [
			'sql' => "int(10) unsigned NOT NULL default '0'",
		],
		'name' => [
			'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['field_name'],
			'exclude' => true,
			'search' => true,
			'sorting' => true,
			'filter' => true,
			'flag' => 11,
			'inputType' => 'text',
			'eval' => [
				'mandatory' => true,
				'maxlength' => 80,
				'unique' => false,
				'tl_class' => 'w50',
			],
			'sql' => "varchar(80) NOT NULL default ''",
		],
		'start_time' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['field_start_time'],
			'default' => '',
			'exclude' => true,
			'sorting' => true,
			'inputType' => 'text',
			'eval' => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'default' => 1636012800,
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'online_registration_enabled' => [
			'label' => &$GLOBALS['TL_LANG']['tl_runregistration_track']['field_online_registration_enabled'],
			'exclude' => true,
			'search' => false,
			'sorting' => false,
			'filter' => false,
			'inputType' => 'checkbox',
			'eval' => [
				'mandatory' => false,
				'tl_class' => 'w50',
			],
			'sql' => "char(1) NOT NULL default ''",
		],
	],
];

class tl_runregistration_track extends Backend
{
	public function formatLabel($row, $label, DataContainer $dc, $args)
	{
		$args[0] = '<img src="/bundles/runregistration/medal_gold_2.png" alt="" />&nbsp;'.$row['name'];
		$args[1] = '<img src="/bundles/runregistration/clock.png" alt="" />&nbsp;'.date('H:i', $row['start_time']).' Uhr';
		return $args;
	}
}
?>
