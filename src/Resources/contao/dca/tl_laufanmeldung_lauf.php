<?php
	$GLOBALS['TL_DCA']['tl_laufanmeldung_lauf'] = [
		'config' => [
			'dataContainer' => 'Table',
			'ctable' => 'tl_laufanmeldung_strecke',
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
				'label_callback' => array('tl_laufanmeldung_lauf', 'formatLabel'),
			],
			'global_operations' => [],
			'operations' => [
				'edit' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['editstrecke'],
					'href' => 'table=tl_laufanmeldung_strecke',
					'icon' => '/bundles/laufanmeldung/map.png',
				],
				'editheader' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['edit'],
					'href' => 'act=edit',
					'icon' => 'edit.svg',
				],
				'delete' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['delete'],
					'href' => 'act=delete',
					'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
					'icon' => 'delete.svg',
				],
			],
		],
		'palettes' => [
			'__selector__' => [],
			'default' => '{title_general},date,name;{title_anmeldezeit},anmeldungstart,anmeldungende',
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
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['field_date'],
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
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['field_name'],
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
				'sql' => "varchar(80) NOT NULL",
			],
			'anmeldungstart' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['field_anmeldungstart'],
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
			'anmeldungende' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_lauf']['field_anmeldungende'],
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
				'sql' => "varchar(10) NOT NULL",
			],
		],
	];
	
	class tl_laufanmeldung_lauf extends Backend
	{
		public function formatLabel($row, $label, DataContainer $dc, $args)
		{
			$args[0] = '<img src="/bundles/laufanmeldung/calendar.png" alt="" />&nbsp;'.date('d.m.Y', $row['date']);
			$args[1] = '<img src="/bundles/laufanmeldung/medal_gold_2.png" alt="" />&nbsp;'.$row['name'];
			return $args;
		}
	}
?>