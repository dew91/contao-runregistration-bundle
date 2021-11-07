<?php
/*
 * Contao Runregistration bundle.
 *
 * (c) 2016-2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

namespace Dew91\ContaoRunregistrationBundle;

class RunregistrationTrackModel extends \Contao\Model
{
	protected static $strTable = 'tl_runregistration_track';

	public static function getTracksByRunId($run_id)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?", "$t.online_registration_enabled=1");

		return static::findBy($arrColumns, $run_id);
	}
}
?>
