<?php
	// === HIER BITTE EINSTELLUNGEN ANPASSEN ===
	
	// Google reCaptcha Public Site Key
	$GLOBALS['DominicErnst']['LaufanmeldungBundle']['reCaptcha_SiteKey'] = '6Lc71wsUAAAAAK8nMqFhJ_NdpQXy4dkbE9py3P6H';
	// Google reCaptcha Private Secret
	$GLOBALS['DominicErnst']['LaufanmeldungBundle']['reCaptcha_Secret'] = '6Lc71wsUAAAAAB26mcmf8l9tlau2U-YvYe96GGnr';
	
	// Bestätigung der Anmeldung notwendig?
	$GLOBALS['DominicErnst']['LaufanmeldungBundle']['AktivierungNotwendig'] = FALSE;
	
	// absolute oder relative URL zur Datenschutzerklärung
	$GLOBALS['DominicErnst']['LaufanmeldungBundle']['URL_DPR'] = '/datenschutz.html';
	
	// === AB HIER NICHTS MEHR ÄNDERN ===

	// Backend-Module registrieren
	array_insert($GLOBALS['BE_MOD']['content'], 2, array
	(
		'Laufanmeldung' => array
		(
			'tables' => array('tl_laufanmeldung_lauf', 'tl_laufanmeldung_strecke', 'tl_laufanmeldung_teilnehmer'),
			'exportcsv' => array('\DominicErnst\LaufanmeldungBundle\Classes\LaufanmeldungExport', 'ShowExportSettings')
		)
	));
	
	// Frontend-Module registrieren
	$GLOBALS['FE_MOD']['Laufanmeldung']['ModuleLaufanmeldungFormular'] = 'DominicErnst\\LaufanmeldungBundle\\Modules\\ModuleLaufanmeldungFormular';
	
	// Modelle registrieren
	$GLOBALS['TL_MODELS']['tl_laufanmeldung_lauf'] = '\DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungLaufModel';
	$GLOBALS['TL_MODELS']['tl_laufanmeldung_strecke'] = '\DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungStreckeModel';
	$GLOBALS['TL_MODELS']['tl_laufanmeldung_teilnehmer'] = '\DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungTeilnehmerModel';
?>