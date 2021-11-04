<?php
namespace DominicErnst\LaufanmeldungBundle\Classes;

use DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungTeilnehmerModel;
use DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungStreckeModel;

class LaufanmeldungExport extends \Backend
{
	public function ShowExportSettings()
	{
		if(\Input::post('export'))
		{
			$exportErrors = array();
			
			// Feldtrenner überprüfen
			$sep = trim(\Input::post('separator'));
			if(!in_array($sep, array(',', ';')))
				$exportErrors[] = '&bull; '.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_error_separator'];
			
			// Zeichensatz
			$enc = trim(\Input::post('encoding'));
			if(!in_array($enc, array('Windows-1252', 'UTF-8')))
				$exportErrors[] = '&bull; '.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_error_charset'];
			
			// Fehler aufgetreten?
			if(!count($exportErrors))
			{
				// Keine Fehler, Export starten!
				return $this->OutputCSV($sep, $enc);
			}
		}
		
		$strecke = LaufanmeldungStreckeModel::findById(\Input::get('id'));
		
		return '<div id="tl_buttons">
				<a class="header_back" href="contao?do=Laufanmeldung&table=tl_laufanmeldung_strecke&id='.$strecke->pid.'" title="'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['button_back'][1].'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['button_back'][0].'</a>
			</div>
			<form action="'.ampersand(\Environment::get('request'), true).'" id="LaufanmeldungExport" class="tl_form" method="post">
			<div class="tl_formbody_edit">
				<input type="hidden" name="FORM_SUBMIT" value="LaufanmeldungExport">
				<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
				<fieldset class="tl_tbox nolegend">
					<div>
						'.(count($exportErrors)?'<p>'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_errors_pretext'].'<ul><li>'.join('</li><li>', $exportErrors).'</ul></p>':'').'
						<h3><label for="separator">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_label_separator'][0].'</label></h3>
						<select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset()">
							<option value=";">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_separator_semicolon'].'</option>
							<option value=",">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_separator_comma'].'</option>
						</select>
						<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_label_separator'][1].'</p>
						<h3><label for="encoding">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_label_charset'][0].'</label></h3>
						<select name="encoding" id="encoding" class="tl_select" onfocus="Backend.getScrollOffset()">
							<option value="Windows-1252">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_charset_Windows-1252'].'</option>
							<option value="UTF-8">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_charset_UTF-8'].'</option>
						</select>
						<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_label_charset'][1].'</p>
					</div>
				</fieldset>
			</div>
			<div class="tl_formbody_submit">
				<div class="tl_submit_container">
					<button type="submit" name="export" value="laDataExport" id="export" class="tl_submit" accesskey="x">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['button_export'].'</button>
				</div>
			</div>
		</form>';
	}
	
	public function OutputCSV($sep, $encoding)
	{
		$strecke_id = \Input::get('id');
		$strecke = LaufanmeldungStreckeModel::findById($strecke_id);
		
		if(($objRunners = LaufanmeldungTeilnehmerModel::findByPid($strecke_id)) !== NULL)
		{
			header("Content-Type: text/csv");
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="'.$strecke->name.'.csv"');
			
			echo mb_convert_encoding('"ID"'.$sep.'"Strecke"'.$sep.'"Nachname"'.$sep.'"Vorname"'.$sep.'"Team/Verein"'.$sep.'"Geschlecht"'.$sep.'"Geburtstag"'.$sep.'"Adresse"'.$sep.'"PLZ"'.$sep.'"Ort"'.$sep.'"E-Mail"'.$sep.'"Anmeldezeit"', $encoding, 'UTF-8');
			while($objRunners->next())
			{
				if($objRunners->anmeldungaktiv =='0') continue;
				echo mb_convert_encoding("\n".$objRunners->id.$sep.'"'.$strecke->name.'"'.$sep.'"'.$objRunners->name.'"'.$sep.'"'.$objRunners->vorname.'"'.$sep.'"'.$objRunners->verein.'"'.$sep.'"'.$objRunners->geschlecht.'"'.$sep.'"'.date('d.m.Y', $objRunners->geburtstag).'"'.$sep.'"'.$objRunners->strasse.'"'.$sep.'"'.$objRunners->plz.'"'.$sep.'"'.$objRunners->ort.'"'.$sep.'"'.$objRunners->email.'"'.$sep.'"'.date('d.m.Y H:i:s', $objRunners->zeitanmeldung).'"', $encoding, 'UTF-8');
			}
		} else {
			return '<div id="tl_buttons">
				<a class="header_back" href="contao?do=Laufanmeldung&table=tl_laufanmeldung_strecke&id='.$strecke->pid.'" title="'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['button_back'][1].'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['button_back'][0].'</a>
			</div>
			<div class="tl_formbody_edit">
				<fieldset class="tl_tbox nolegend">
					<div>
						<p><strong>'.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_word_error'].'</strong> '.$GLOBALS['TL_LANG']['tl_laufanmeldung_strecke']['export_error_nodata'].'</p>
					</div>
				</fieldset>
			</div>';
		}
		exit;
	}
}

?>