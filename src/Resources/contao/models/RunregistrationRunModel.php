<?php
/**
 * Model für Laufanmeldungen
 *
 * Copyright (c) 2016 Dominic Ernst
 */
 
namespace Dew91\ContaoRunregistrationBundle;
 
class RunregistrationRunModel extends \Model
{
	// Tabellenname
	protected static $strTable = 'tl_runregistration_run';
	
	// Funktion zum auflisten aller Laufanmeldeformulare
	public static function getRunById($run_id)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		return static::findBy($arrColumns, $run_id);
	}
}
?>
