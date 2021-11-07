<?php
/*
 * Contao Runregistration bundle.
 *
 * (c) 2016-2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

namespace Dew91\ContaoRunregistrationBundle;

class RunregistrationRunModel extends \Model
{
	protected static $strTable = 'tl_runregistration_run';
	
	public static function getRunById($run_id)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		return static::findBy($arrColumns, $run_id);
	}
}
?>
