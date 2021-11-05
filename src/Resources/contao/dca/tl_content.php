<?php
	$GLOBALS['TL_DCA']['tl_content']['palettes']['runregistrationForm'] = '{type_legend},type;{include_legend},runregistrationRun';
	$GLOBALS['TL_DCA']['tl_content']['fields']['runregistrationRun'] = array(
		'exclude'                 => true,
		'inputType'               => 'select',
		'options_callback'        => array('tl_content_runregistrationRun', 'getOptions'),
		'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50 wizard'),
		'sql'                     => "int(10) unsigned NOT NULL default 0"
	);

	class tl_content_runregistrationRun extends Contao\Backend
	{
		public function getOptions()
		{
			$runs = array();
			$objRuns = $this->Database->execute("SELECT id, date, name FROM tl_runregistration_run ORDER BY date ASC");
			while ($objRuns->next()) {
				$runs[$objRuns->id] = date('d.m.Y', $objRuns->date) . ': ' . $objRuns->name . ' (ID '.$objRuns->id.')';
			}
			return $runs;
		}
	}
?>
