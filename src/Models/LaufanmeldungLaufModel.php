<?php
/**
 * Model für Laufanmeldungen
 *
 * Copyright (c) 2016 Dominic Ernst
 */
 
namespace DominicErnst\LaufanmeldungBundle\Models;
 
class LaufanmeldungLaufModel extends \Model
{
	// Tabellenname
	protected static $strTable = 'tl_laufanmeldung_lauf';
	
	// Funktion zum auflisten aller Laufanmeldeformulare
	public static function getLaufdaten($lauf_id)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		return static::findBy($arrColumns, $lauf_id);
	}
}
?>