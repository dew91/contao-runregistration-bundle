<?php
	$GLOBALS['TL_DCA']['tl_laufanmeldung_strecke'] = [
		'config' => [
			'dataContainer' => 'Table',
			'ptable' => 'tl_laufanmeldung_lauf',
			'ctable' => array('tl_laufanmeldung_teilnehmer'),
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
				'fields' => ['start', 'name'],
				'flag' => 11,
				'panelLayout' => 'sort',
			],
			'label' => [
				'fields' => ['name', 'start'],
				'showColumns' => true,
				'format' => '%s',
				'label_callback' => array('tl_laufanmeldung_strecke', 'formatLabel'),
			],
			'global_operations' => [],
			'operations' => [
				'exportcsv' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['exportcsv'],
					'href' => 'key=exportcsv',
					'icon' => '/bundles/laufanmeldung/table_go.png',
				],
				'editteilnehmer' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['editteilnehmer'],
					'href' => 'table=tl_laufanmeldung_teilnehmer',
					'icon' => '/bundles/laufanmeldung/group.png',
				],
				'edit' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['edit'],
					'href' => 'act=edit',
					'icon' => 'edit.svg',
				],
				'delete' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['delete'],
					'href' => 'act=delete',
					'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
					'icon' => 'delete.svg',
				],
			],
		],
		'palettes' => [
			'__selector__' => [],
			'default' => '{title_general},name,start,onlineanmeldung',
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
				'foreignKey' => 'tl_laufanmeldung_lauf.id',
				'sql' => "int(10) unsigned NOT NULL default '0'",
				'relation' => array('type'=>'belongsTo', 'load'=>'eager')
			),
			'tstamp' => [
				'sql' => "int(10) unsigned NOT NULL default '0'",
			],
			'name' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['field_name'],
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
				'sql' => "varchar(80) NOT NULL",
			],
			'start' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['field_start'],
				'default'                 => '',
				'exclude'                 => true,
				'sorting'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
				'sql'                     => "int(10) unsigned NOT NULL default '0'"
			),
			'onlineanmeldung' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['field_onlineanmeldung'],
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
	
	class tl_laufanmeldung_strecke extends Backend
	{
		public function formatLabel($row, $label, DataContainer $dc, $args)
		{
			$args[0] = '<img src="/bundles/laufanmeldung/medal_gold_2.png" alt="" />&nbsp;'.$row['name'];
			$args[1] = '<img src="/bundles/laufanmeldung/clock.png" alt="" />&nbsp;'.date('H:i', $row['start']).' Uhr';
			return $args;
		}
	}
?>