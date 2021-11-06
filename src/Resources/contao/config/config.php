<?php
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
