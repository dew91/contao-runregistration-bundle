<?php
/**
 * Model für Laufanmeldungen
 *
 * Copyright (c) 2016 Dominic Ernst
 */
 
namespace DominicErnst\LaufanmeldungBundle\Models;
 
class LaufanmeldungStreckeModel extends \Model
{
	// Tabellenname
	protected static $strTable = 'tl_laufanmeldung_strecke';
	
	// Funktion zum auflisten aller Laufanmeldeformulare
	public static function getLaufStrecken($lauf_id)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?", "$t.onlineanmeldung=1");

		return static::findBy($arrColumns, $lauf_id);
	}
}
?>