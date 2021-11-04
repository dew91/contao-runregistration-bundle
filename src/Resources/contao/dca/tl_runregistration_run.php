<?php
	$GLOBALS['TL_DCA']['tl_runregistration_run'] = [
		'config' => [
			'dataContainer' => 'Table',
			'ctable' => 'tl_runregistration_track',
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
				'fields' => ['date', 'name'],
				'flag' => 11,
				'panelLayout' => 'sort,search,limit',
			],
			'label' => [
				'fields' => ['date', 'name'],
				'showColumns' => true,
				'format' => '%s',
				'label_callback' => array('tl_runregistration_run', 'formatLabel'),
			],
			'global_operations' => [],
			'operations' => [
				'edit' => [
					'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['edittrack'],
					'href' => 'table=tl_runregistration_track',
					'icon' => '/bundles/contaorunregistration/map.png',
				],
				'editheader' => [
					'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['edit'],
					'href' => 'act=edit',
					'icon' => 'edit.svg',
				],
				'delete' => [
					'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['delete'],
					'href' => 'act=delete',
					'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
					'icon' => 'delete.svg',
				],
			],
		],
		'palettes' => [
			'__selector__' => [],
			'default' => '{title_general},date,name;{title_registration_period},registration_start,registration_end',
		],
		'subpalettes' => [
			'' => '',
		],
		'fields' => [
			'id' => [
				'sql' => "int(10) unsigned NOT NULL auto_increment",
			],
			'tstamp' => [
				'sql' => "int(10) unsigned NOT NULL default '0'",
			],
			'date' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['field_date'],
				'exclude' => true,
				'search' => true,
				'sorting' => true,
				'filter' => true,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'rgxp' => 'date',
					'unique' => true,
					'datepicker' => true,
					'tl_class' => 'w50 wizard',
				],
				'sql' => "int(10) unsigned NULL",
			],
			'name' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['field_name'],
				'exclude' => true,
				'search' => true,
				'sorting' => true,
				'filter' => true,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'maxlength' => 80,
					'unique' => true,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(80) NOT NULL default ''",
			],
			'registration_start' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['field_registration_start'],
				'exclude' => true,
				'search' => false,
				'sorting' => false,
				'filter' => false,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => false,
					'rgxp' => 'datim',
					'datepicker' => true,
					'tl_class' => 'w50 wizard',
				],
				'sql' => "varchar(10) NOT NULL default ''",
			],
			'registration_end' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_run']['field_registration_end'],
				'exclude' => true,
				'search' => false,
				'sorting' => false,
				'filter' => false,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'rgxp' => 'datim',
					'datepicker' => true,
					'tl_class' => 'w50 wizard',
				],
				'sql' => "varchar(10) NOT NULL default ''",
			],
		],
	];
	
	class tl_runregistration_run extends Backend
	{
		public function formatLabel($row, $label, DataContainer $dc, $args)
		{
			$args[0] = '<img src="/bundles/contaorunregistration/calendar.png" alt="" />&nbsp;'.date('d.m.Y', $row['date']);
			$args[1] = '<img src="/bundles/contaorunregistration/medal_gold_2.png" alt="" />&nbsp;'.$row['name'];
			return $args;
		}
	}
?>
