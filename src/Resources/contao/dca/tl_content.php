<?php
	$GLOBALS['TL_DCA']['tl_content']['palettes']['runregistrationForm'] = '{type_legend},type;{include_legend},runregistrationRun';
	$GLOBALS['TL_DCA']['tl_content']['fields']['runregistrationRun'] = array(
		'exclude'                 => true,
		'inputType'               => 'select',
		/*'options_callback'        => array('tl_runregistration_runs', 'getRuns'),*/
		'options'				  				=> array(1 => 'Run A', 2 => 'Run B'),
		'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50 wizard'),
		'sql'                     => "int(10) unsigned NOT NULL default 0"
	);
?>
