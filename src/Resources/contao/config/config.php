<?php
	// === HIER BITTE EINSTELLUNGEN ANPASSEN ===
	
	// Google reCaptcha Public Site Key
	$GLOBALS['Dew91']['RunregistrationBundle']['reCaptcha_SiteKey'] = '6Lc71wsUAAAAAK8nMqFhJ_NdpQXy4dkbE9py3P6H';
	// Google reCaptcha Private Secret
	$GLOBALS['DominicErnst']['RunregistrationBundle']['reCaptcha_Secret'] = '6Lc71wsUAAAAAB26mcmf8l9tlau2U-YvYe96GGnr';
	
	// Bestätigung der Anmeldung notwendig?
	$GLOBALS['DominicErnst']['RunregistrationBundle']['AktivierungNotwendig'] = FALSE;
	
	// absolute oder relative URL zur Datenschutzerklärung
	$GLOBALS['DominicErnst']['RunregistrationBundle']['URL_DPR'] = '/datenschutz.html';
	
	// === AB HIER NICHTS MEHR ÄNDERN ===

	// Backend-Module registrieren
	$GLOBALS['BE_MOD']['content']['runregistration'] = array(
		'tables' => array('tl_runregistration_run', 'tl_runregistration_track', 'tl_runregistration_attendee'),
		'exportcsv' => array('Dew91\ContaoRunregistrationBundle\RunregistrationExport', 'ShowExportSettings')
	);
	
	// Frontend-Module registrieren
	//$GLOBALS['FE_MOD']['RunregistrationBundle']['RunregistrationForm'] = 'DominicErnst\\LaufanmeldungBundle\\Modules\\ModuleLaufanmeldungFormular';
	
	// Modelle registrieren
	$GLOBALS['TL_MODELS']['tl_runregistration_run'] = '\Dew91\ContaoRunregistrationBundle\RunregistrationRunModel';
	$GLOBALS['TL_MODELS']['tl_runregistration_track'] = '\Dew91\ContaoRunregistrationBundle\RunregistrationTrackModel';
	$GLOBALS['TL_MODELS']['tl_runregistration_attendee'] = '\Dew91\ContaoRunregistrationBundle\RunregistrationAttendeeModel';
	
	// Register content elements
	$GLOBALS['TL_CTE']['includes']['runregistrationForm'] = '\Dew91\ContaoRunregistrationBundle\RunregistrationFormElement';
?>
