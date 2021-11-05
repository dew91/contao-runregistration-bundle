<?php
/**
 * Model für Laufanmeldungen
 *
 * Copyright (c) 2016 Dominic Ernst
 */
 
namespace Dew91\ContaoRunregistrationBundle;
 
class RunregistrationTrackModel extends \Contao\Model
{
	// Tabellenname
	protected static $strTable = 'tl_runregistration_track';
	
	public static function getTracksByRunId($run_id)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?", "$t.online_registration_enabled=1");

		return static::findBy($arrColumns, $run_id);
	}
}
?>
