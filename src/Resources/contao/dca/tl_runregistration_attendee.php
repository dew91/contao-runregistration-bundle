<?php
	$GLOBALS['TL_DCA']['tl_runregistration_attendee'] = [
		'config' => [
			'dataContainer' => 'Table',
			'ptable' => 'tl_runregistration_track',
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
				'fields' => ['last_name', 'first_name', 'club'],
				'flag' => 11,
				'panelLayout' => 'sort,filter;search,limit',
			],
			'label' => [
				'fields' => ['last_name', 'club'],
				'showColumns' => true,
				'format' => '%s',
				'label_callback' => array('tl_runregistration_attendee', 'formatLabel'),
			],
			'global_operations' => [],
			'operations' => [
				'edit' => [
					'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['edit'],
					'href' => 'act=edit',
					'icon' => 'edit.svg',
				],
				'delete' => [
					'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['delete'],
					'href' => 'act=delete',
					'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
					'icon' => 'delete.svg',
				],
				'show' => [
					'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['show'],
					'href' => 'act=show',
					'icon' => 'show.svg'
				],
			],
		],
		'palettes' => [
			'__selector__' => [],
			'default' => '{title_personal},last_name,first_name,gender,birthday;{title_address},email,street,zip,city;{title_run},club,registration_timestamp,registration_confirmed',
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
				'foreignKey' => 'tl_runregistration_track.id',
				'sql' => "int(10) unsigned NOT NULL default '0'",
				'relation' => array('type'=>'belongsTo', 'load'=>'eager')
			),
			'tstamp' => [
				'sql' => "int(10) unsigned NOT NULL default '0'",
			],
			'last_name' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_last_name'],
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
				'sql' => "varchar(50) NOT NULL default ''",
			],
			'first_name' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_first_name'],
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
				'sql' => "varchar(50) NOT NULL default ''",
			],
			'gender' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_gender'],
				'exclude' => false,
				'search' => false,
				'sorting' => true,
				'filter' => true,
				'flag' => 2,
				'inputType' => 'select',
				'options' => array('m' => 'm&auml;nnlich', 'f' => 'weiblich'),
				'eval' => [
					'mandatory' => true,
					'maxlength' => 50,
					'tl_class' => 'w50',
				],
				'sql' => "char(1) NOT NULL default 'm'",
			],
			'birthday' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_birthday'],
				'exclude' => false,
				'inputType' => 'text',
				'eval' => array('mandatory' => true, 'rgxp'=>'date', 'datepicker' => true, 'doNotCopy'=>true, 'tl_class'=>'w50 wizard'),
				'sql' => "int(11) NOT NULL default '0'"
			),
			'email' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_email'],
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
				'sql' => "varchar(50) NOT NULL default ''",
			],
			'street' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_street'],
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
				'sql' => "varchar(50) NOT NULL default ''",
			],
			'zip' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_zip'],
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
				'sql' => "varchar(5) NOT NULL default ''",
			],
			'city' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_city'],
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
				'sql' => "varchar(50) NOT NULL default ''",
			],
			'club' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_club'],
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
				'sql' => "varchar(50) NOT NULL default ''",
			],
			'registration_timestamp' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_registration_timestamp'],
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
			'registration_ip' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_registration_ip'],
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
			'registration_confirmed' => [
				'label' => &$GLOBALS['TL_LANG']['tl_runregistration_attendee']['field_registration_confirmed'],
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
	
	class tl_runregistration_attendee extends Backend
	{
		public function formatLabel($row, $label, DataContainer $dc, $args)
		{
			// Select icon by gender
			if($row['gender'][0] =='m')
				$iconsrc = '/bundles/contaorunregistration/user.png';
			else
				$iconsrc = '/bundles/contaorunregistration/user_female.png';
			
			$args[0] = '<img src="'.$iconsrc.'" alt="" />&nbsp;'.$row['last_name'].', '.$row['first_name'];
			return $args;
		}
	}
?>
