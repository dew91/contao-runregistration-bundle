<?php
/*
 * Contao Runregistration bundle.
 *
 * (c) 2016-2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['ModuleRunregistrationForm'] = '{title_legend},name,type;{config_legend},runregistration';
$GLOBALS['TL_DCA']['tl_module']['fields']['runregistration'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['runregistration'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_runregistration', 'getRuns'),
	'eval'                    => array('multiple'=>false, 'mandatory'=>true),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

class tl_module_runregistration extends Backend
{
	public function getRuns()
	{
		$arrRuns = array();
		$objRuns = $this->Database->execute("SELECT id, name FROM tl_runregistration_run ORDER BY date");

		while ($objRuns->next())
		{
			$arrRuns[$objRuns->id] = $objRuns->name.' [ID: '.$objRuns->id.']';
		}

		return $arrRuns;
	}
}
?>
