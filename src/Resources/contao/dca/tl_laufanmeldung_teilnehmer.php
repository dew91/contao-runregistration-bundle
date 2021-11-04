<?php
	$GLOBALS['TL_DCA']['tl_laufanmeldung_teilnehmer'] = [
		'config' => [
			'dataContainer' => 'Table',
			'ptable' => 'tl_laufanmeldung_strecke',
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
				'fields' => ['name', 'vorname', 'verein'],
				'flag' => 11,
				'panelLayout' => 'sort,filter;search,limit',
			],
			'label' => [
				'fields' => ['name', 'verein'],
				'showColumns' => true,
				'format' => '%s',
				'label_callback' => array('tl_laufanmeldung_teilnehmer', 'formatLabel'),
			],
			'global_operations' => [],
			'operations' => [
				'edit' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['edit'],
					'href' => 'act=edit',
					'icon' => 'edit.svg',
				],
				'delete' => [
					'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['delete'],
					'href' => 'act=delete',
					'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
					'icon' => 'delete.svg',
				],
				'show' => [
					'label'               => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['show'],
					'href'                => 'act=show',
					'icon'                => 'show.svg'
				],
			],
		],
		'palettes' => [
			'__selector__' => [],
			'default' => '{title_personal},name,vorname,geschlecht,geburtstag;{title_address},email,strasse,plz,ort;{title_run},verein,zeitanmeldung,anmeldungaktiv',
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
				'foreignKey' => 'tl_laufanmeldung_strecke.id',
				'sql' => "int(10) unsigned NOT NULL default '0'",
				'relation' => array('type'=>'belongsTo', 'load'=>'eager')
			),
			'tstamp' => [
				'sql' => "int(10) unsigned NOT NULL default '0'",
			],
			'name' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_name'],
				'exclude' => false,
				'search' => true,
				'sorting' => true,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(50) NOT NULL",
			],
			'vorname' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_vorname'],
				'exclude' => false,
				'search' => true,
				'sorting' => true,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(50) NOT NULL",
			],
			'geschlecht' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_geschlecht'],
				'exclude' => false,
				'search' => false,
				'sorting' => true,
				'filter' => true,
				'flag' => 2,
				'inputType' => 'select',
				'options' => array('m' => 'm&auml;nnlich', 'w' => 'weiblich'),
				'eval' => [
					'mandatory' => true,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "char(1) NOT NULL",
			],
			'geburtstag' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_geburtstag'],
				'exclude'                 => false,
				'inputType'               => 'text',
				'eval'                    => array('mandatory' => true, 'rgxp'=>'date', 'datepicker' => true, 'doNotCopy'=>true, 'tl_class'=>'w50 wizard'),
				'sql'                     => "int(11) NOT NULL default '0'"
			),
			'email' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_email'],
				'exclude' => false,
				'search' => true,
				'sorting' => false,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(50) NOT NULL",
			],
			'strasse' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_strasse'],
				'exclude' => false,
				'search' => false,
				'sorting' => false,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => false,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(50) NOT NULL",
			],
			'plz' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_plz'],
				'exclude' => false,
				'search' => false,
				'sorting' => false,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => false,
					'maxlength' => 5,
					'rgxp' => 'natural',
					'tl_class' => 'w50',
				],
				'sql' => "varchar(5) NOT NULL",
			],
			'ort' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_ort'],
				'exclude' => false,
				'search' => false,
				'sorting' => false,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => false,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(50) NOT NULL",
			],
			'verein' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_verein'],
				'exclude' => false,
				'search' => true,
				'sorting' => true,
				'filter' => false,
				'flag' => 11,
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "varchar(50) NOT NULL",
			],
			'zeitanmeldung' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_zeitanmeldung'],
				'exclude' => true,
				'search' => false,
				'sorting' => true,
				'filter' => false,
				'flag' => 11,
				'default' => time(),
				'inputType' => 'text',
				'eval' => [
					'mandatory' => true,
					'rgxp' => 'datim',
					'datepicker' => true,
					'tl_class' => 'w50 wizard',
				],
				'sql' => "int(10) unsigned NOT NULL default '0'",
			],
			'ipanmeldung' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_ipanmeldung'],
				'exclude' => true,
				'search' => false,
				'sorting' => false,
				'filter' => false,
				'default' => \Environment::get('remoteAddr'),
				'inputType' => 'text',
				'eval' => [
					'mandatory' => false,
				],
				'sql' => "varchar(15) NOT NULL default '0.0.0.0'",
				
			],
			'anmeldungaktiv' => [
				'label' => &$GLOBALS['TL_LANG']['tl_laufanmeldung_teilnehmer']['field_anmeldungaktiv'],
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
	
	class tl_laufanmeldung_teilnehmer extends Backend
	{
		public function formatLabel($row, $label, DataContainer $dc, $args)
		{
			// Icon je nach Geschlecht festlegen
			if($row['geschlecht'][0] =='m')
				$iconsrc = '/bundles/laufanmeldung/user.png';
			else
				$iconsrc = '/bundles/laufanmeldung/user_female.png';
			
			$args[0] = '<img src="'.$iconsrc.'" alt="" />&nbsp;'.$row['name'].', '.$row['vorname'];
			return $args;
		}
	}
?>