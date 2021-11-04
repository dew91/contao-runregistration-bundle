<?php
/**
 * Eigene Palett zu tl_modules hinzufügen
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['ModuleLaufanmeldungFormular'] = '{title_legend},name,type;{config_legend},laufanmeldung';

/**
 * Eigene Felder zur Modultabelle hinzufügen
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['laufanmeldung'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['laufanmeldung'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_laufanmeldung', 'getLaeufe'),
	'eval'                    => array('multiple'=>false, 'mandatory'=>true),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

class tl_module_laufanmeldung extends Backend
{
	/**
	 * Get all runs and return them as array
	 *
	 * @return array
	 */
	public function getLaeufe()
	{
		$arrLaeufe = array();
		$objLaeufe = $this->Database->execute("SELECT id, name FROM tl_laufanmeldung_lauf ORDER BY date");

		while ($objLaeufe->next())
		{
			$arrLaeufe[$objLaeufe->id] = $objLaeufe->name.' [ID: '.$objLaeufe->id.']';
		}

		return $arrLaeufe;
	}
}
?>