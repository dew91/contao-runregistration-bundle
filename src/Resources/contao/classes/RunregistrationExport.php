<?php
/*
 * Contao Runregistration bundle.
 *
 * (c) 2016-2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

namespace Dew91\ContaoRunregistrationBundle;

class RunregistrationExport extends \Backend
{
	public function ShowExportSettings()
	{
		$exportErrors = array();
		if(\Input::post('export'))
		{
			// Feldtrenner überprüfen
			$sep = trim(\Input::post('separator'));
			if(!in_array($sep, array(',', ';')))
				$exportErrors[] = '&bull; '.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_error_separator'];

			// Zeichensatz
			$enc = trim(\Input::post('encoding'));
			if(!in_array($enc, array('Windows-1252', 'UTF-8')))
				$exportErrors[] = '&bull; '.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_error_charset'];

			// Fehler aufgetreten?
			if(!count($exportErrors))
			{
				// Keine Fehler, Export starten!
				return $this->OutputCSV($sep, $enc);
			}
		}

		$track = RunregistrationTrackModel::findById(\Input::get('id'));

		return '<div id="tl_buttons">
				<a class="header_back" href="contao?do=runregistration&table=tl_runregistration_track&id='.$track->pid.'" title="'.$GLOBALS['TL_LANG']['tl_runregistration_track']['button_back'][1].'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['button_back'][0].'</a>
			</div>
			<form action="'.ampersand(\Environment::get('request'), true).'" id="RunregistrationExport" class="tl_form" method="post">
			<div class="tl_formbody_edit">
				<input type="hidden" name="FORM_SUBMIT" value="RunregistrationExport">
				<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
				<fieldset class="tl_tbox nolegend">
					'.(count($exportErrors)?'<div class="widget"><p>'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_errors_pretext'].'<ul><li>'.join('</li><li>', $exportErrors).'</ul></p></div>':'').'
					<div class="w50 widget">
						<h3><label for="separator">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_label_separator'][0].'</label></h3>
						<select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset()">
							<option value=";">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_separator_semicolon'].'</option>
							<option value=",">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_separator_comma'].'</option>
						</select>
						<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_label_separator'][1].'</p>
					</div>
					<div class="w50 widget">
						<h3><label for="encoding">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_label_charset'][0].'</label></h3>
						<select name="encoding" id="encoding" class="tl_select" onfocus="Backend.getScrollOffset()">
							<option value="Windows-1252">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_charset_Windows-1252'].'</option>
							<option value="UTF-8">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_charset_UTF-8'].'</option>
						</select>
						<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_label_charset'][1].'</p>
					</div>
				</fieldset>
			</div>
			<div class="tl_formbody_submit">
				<div class="tl_submit_container">
					<button type="submit" name="export" value="laDataExport" id="export" class="tl_submit" accesskey="x">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['button_export'].'</button>
				</div>
			</div>
		</form>';
	}

	public function OutputCSV($sep, $encoding)
	{
		$track_id = \Input::get('id');
		$track = RunregistrationTrackModel::findById($track_id);

		if(($objAttendees = RunregistrationAttendeeModel::findByPid($track_id)) !== NULL)
		{
			header("Content-Type: text/csv");
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="'.$track->name.'.csv"');

			echo mb_convert_encoding('"ID"'.$sep.'"Strecke"'.$sep.'"Nachname"'.$sep.'"Vorname"'.$sep.'"Team/Verein"'.$sep.'"Geschlecht"'.$sep.'"Geburtstag"'.$sep.'"Adresse"'.$sep.'"PLZ"'.$sep.'"Ort"'.$sep.'"E-Mail"'.$sep.'"Anmeldezeit"', $encoding, 'UTF-8');
			while($objAttendees->next())
			{
				if($objAttendees->registration_confirmed =='0') continue;
				echo mb_convert_encoding("\n".$objAttendees->id.$sep.'"'.$track->name.'"'.$sep.'"'.$objAttendees->last_name.'"'.$sep.'"'.$objAttendees->first_name.'"'.$sep.'"'.$objAttendees->club.'"'.$sep.'"'.$objAttendees->gender.'"'.$sep.'"'.date('d.m.Y', $objAttendees->birthday).'"'.$sep.'"'.$objAttendees->street.'"'.$sep.'"'.$objAttendees->zip.'"'.$sep.'"'.$objAttendees->city.'"'.$sep.'"'.$objAttendees->email.'"'.$sep.'"'.date('d.m.Y H:i:s', $objAttendees->registration_timestamp).'"', $encoding, 'UTF-8');
			}
		} else {
			return '<div id="tl_buttons">
				<a class="header_back" href="contao?do=runregistration&table=tl_runregistration_track&id='.$track->pid.'" title="'.$GLOBALS['TL_LANG']['tl_runregistration_track']['button_back'][1].'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['tl_runregistration_track']['button_back'][0].'</a>
			</div>
			<div class="tl_formbody_edit">
				<fieldset class="tl_tbox nolegend">
					<div>
						<p><strong>'.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_word_error'].'</strong> '.$GLOBALS['TL_LANG']['tl_runregistration_track']['export_error_nodata'].'</p>
					</div>
				</fieldset>
			</div>';
		}
		exit;
	}
}

?>
