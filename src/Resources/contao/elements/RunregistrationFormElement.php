<?php
/*
 * Contao Runregistration bundle.
 *
 * (c) 2016-2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

namespace Dew91\ContaoRunregistrationBundle;
use Contao;

class RunregistrationFormElement extends \ContentElement
{
  protected $strTable = 'tl_content';
  protected $strTemplate = 'ce_runregistration_form';

  private $runId;
  private $runObj;
  private $validated;

  public function __construct($objElement, $strColumn='main')
  {
    parent::__construct($objElement, $strColumn);
    $this->runId  = $this->runregistrationRun;
    $this->runObj = $this->getRunObj();
  }

  public function generate()
  {
    $request = \System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && \System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '<strong>Lauf:</strong> '.$this->runObj->name .' (ID: '.$this->runObj->id.')';
			$objTemplate->id = $this->id;
			return $objTemplate->parse();
		}

		if ($this->customTpl)
		{
			$request = \System::getContainer()->get('request_stack')->getCurrentRequest();

			// Use the custom template unless it is a back end request
			if (!$request || !\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
			{
				$this->strTemplate = $this->customTpl;
			}
		}

		return parent::generate();
  }

  protected function compile()
  {
    // === ALLGEMEINE FORMULARDATEN ÜBERGEBEN ===
		// URL zur Datenschutzerklärung
		$this->Template->url_dpr = $GLOBALS['DominicErnst']['LaufanmeldungBundle']['URL_DPR'];

		$this->Template->runObj = $this->getRunObj();
		$this->Template->runTracks = $this->getRunTracksArray();
		$this->Template->showForm = true;
    $this->Template->validationErrors = array();

		// Array für validierte Daten vorbereiten
		$this->validated = array(
			'runregRunName'		=> $this->Template->runObj->name,
			'runregGender'		=> 'm',
			'runregFirstName'			=> '',
			'runregLastname'		=> '',
			'runregBirthday'		=> '',
			'runregStreet'			=> '',
			'runregZip'				=> '',
			'runregCity'				=> '',
			'runregEmail'			=> '',
			'runregTrack'			=> '',
			'runregClub'			=> '',
			'runregGdpr'				=> '',
			'runregIp'				=> \Environment::get('remoteAddr'),
		);

		// Formular abgesendet?
		if(\Input::post('runregGo'))
		{
			$this->Template->validationErrors = $this->validateForm();
			if(!count($this->Template->validationErrors))
			{
				// Daten korrekt, Anmeldeformular ausblenden
				$this->Template->showForm = false;

				// Datensatz in Datenbank erstellen
				$objRunner = new RunregistrationAttendeeModel();
				$objRunner->pid = $this->validated['runregTrack'];
				$objRunner->tstamp = time();
				$objRunner->last_name = $this->validated['runregLastName'];
				$objRunner->first_name = $this->validated['runregFirstName'];
				$objRunner->gender = $this->validated['runregGender'];
				$objRunner->birthday = strtotime(str_replace('.', '-', $this->validated['runregBirthday']));
				$objRunner->email = $this->validated['runregEmail'];
				$objRunner->street = $this->validated['runregStreet'];
				$objRunner->zip = $this->validated['runregZip'];
				$objRunner->city = $this->validated['runregCity'];
				$objRunner->club = $this->validated['runregClub'];
				$objRunner->registration_timestamp = time();
				$objRunner->registration_ip = $this->validated['runregIp'];
				$objRunner->registration_confirmed = '1';
				$objRunner->save();

				// Anmeldebestätigung generieren
				$htmlmail = '<!DOCTYPE html><html><head><title>Anmeldebestägung</title></head><body style="font-family:Arial;">';
				$htmlmail .= 'Hallo '.$this->validated['runregFirstName'].',<br /><br />hiermit bestägen wir dir deine Anmeldung für folgende Laufveranstaltung:<br /><strong>'.$this->validated['runregRunName'].'</strong><br /><br />Hier nochmal eine Zusammenfassung deiner Daten:<br /><br />';
				$htmlmail .= '<strong><u>PERSÖNLICHE DATEN</u></strong><br />';
				$htmlmail .= 'Geschlecht: <i>'.($this->validated['runregGender']=='m'?'männlich':'weiblich').'</i><br />';
				$htmlmail .= 'Vorname: <i>'.$this->validated['runregFirstName'].'</i><br />';
				$htmlmail .= 'Nachname: <i>'.$this->validated['runregLastname'].'</i><br />';
				$htmlmail .= 'Geburtstag: <i>'.$this->validated['runregBirthday'].'</i><br />';
				if($this->validated['runregStreet'] !='') $htmlmail .= 'Straße: <i>'.$this->validated['runregStreet'].'</i><br />';
				if($this->validated['runregZip'] !='') $htmlmail .= 'PLZ: <i>'.$this->validated['runregZip'].'</i><br />';
				if($this->validated['runregCity'] !='') $htmlmail .= 'Ort: <i>'.$this->validated['runregCity'].'</i><br />';
				$htmlmail .= 'E-Mail: <i>'.$this->validated['runregEmail'].'</i><br /><br />';
				$htmlmail .= '<strong><u>ANGABEN ZUR VERANSTALTUNG</u></strong><br />';
				$htmlmail .= 'Datum: <i>'.date('d.m.Y', $this->Template->runObj->date).'</i><br />';
				$htmlmail .= 'Strecke: <i>'.$this->Template->runTracks[$this->validated['runregTrack']].'</i><br />';
				$htmlmail .= 'Verein/Team/Ort: <i>'.$this->validated['runregClub'].'</i><br /><br />';
				$htmlmail .= 'Die Startunterlagen können am Tag des Laufes an der Anmeldung abgeholt werden, bringen Sie dazu bitte einen Ausdruck dieser E-Mail mit.<br /><br />Viele Grüße<br />Dein Team der Mittelsächsischen Lauftour';
				$htmlmail .= '</body></html>';

				// Anmeldebestätigung per E-Mail senden
				$objEmail = new \Email();
				$objEmail->charset = 'UTF-8';
				$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
				$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
				$objEmail->subject = 'Ihre Anmeldung: '.$this->validated['runregRunName'];
				$objEmail->html = $htmlmail;
				$objEmail->sendTo($this->validated['runregEmail']);
			}
		}

		$this->Template->validated = $this->validated;
    $this->captchaGenerate();
  }

  public function validateForm()
	{
		$validationErrors = array();

    // Captcha
    if (!$this->captchaVerify())
      $validationErrors[] = 'Bitte Sicherheitsfrage richtig beantworten!';

		// Gender
		$this->validated['runregGender'] = trim(\Input::post('runregGender'));
		if(!in_array($this->validated['runregGender'], array('m', 'f')))
			$validationErrors[] = 'Bitte Geschlecht ausw&auml;hlen';

		// First name
		$this->validated['runregFirstName'] = trim(\Input::post('runregFirstName'));
		if(strlen($this->validated['runregFirstName']) < 2 || strlen($this->validated['runregFirstName']) > 50)
			$validationErrors[] = 'Bitte g&uuml;ltigen Vorname eingeben (2-50 Zeichen)';

		// Last name
		$this->validated['runregLastName'] = trim(\Input::post('runregLastName'));
		if(strlen($this->validated['runregLastName']) < 2 || strlen($this->validated['runregLastName']) > 50)
			$validationErrors[] = 'Bitte g&uuml;ltigen Nachname eingeben (2-50 Zeichen)';

		// Birthday
		$this->validated['runregBirthday'] = trim(\Input::post('runregBirthday'));
		if(!preg_match('/^([0-3][0-9])\.([0-1][0-9])\.([1-2][901][0-9][0-9])$/', $this->validated['runregBirthday'], $_date) || !checkdate($_date[2], $_date[1], $_date[3]) || strtotime("$_date[1]-$_date[2]-$_date[3]") >= time())
			$validationErrors[] = 'Bitte g&uuml;tiges Geburtstdatum eingeben';

		// Street and number
		$this->validated['runregStreet'] = trim(\Input::post('runregStreet'));
		if($this->validated['runregStreet'] != '' && (strlen($this->validated['runregStreet']) < 5 || strlen($this->validated['runregStreet']) > 50 || count(explode(' ', $this->validated['runregStreet'])) < 2))
			$validationErrors[] = 'Bitte eine g&uuml;ltige Stra&szlig;e und Hausnummer eingeben (5-50 Zeichen)';

		// Zip
		$this->validated['runregZip'] = trim(\Input::post('runregZip'));
		if($this->validated['runregZip'] != '' && (!preg_match('/^[0-9]+$/', $this->validated['runregZip']) || strlen($this->validated['runregZip']) != 5))
			$validationErrors[] = 'Bitte eine g&uuml;ltige Postleitzahl eingeben (5 Zeichen)';

		// City
		$this->validated['runregCity'] = trim(\Input::post('runregCity'));
		if($this->validated['runregCity'] != '' && (strlen($this->validated['runregCity']) < 3 || strlen($this->validated['runregCity']) > 50))
			$validationErrors[] = 'Bitte einen g&uuml;ltigen Wohnort angeben (3-50 Zeichen)';

		// E-Mail
		$this->validated['runregEmail'] = trim(\Input::post('runregEmail'));
		if(!filter_var($this->validated['runregEmail'], FILTER_VALIDATE_EMAIL))
			$validationErrors[] = 'Bitte eine g&uuml;ltige E-Mail-Adresse eingeben (10-50 Zeichen)';

		// Track
		$this->validated['runregTrack'] = trim(\Input::post('runregTrack'));
		if(!preg_match('/^[0-9]+$/', $this->validated['runregTrack']) || !isset($this->Template->runTracks[$this->validated['runregTrack']]))
			$validationErrors[] = 'Bitte g&uuml;ltige Strecke ausw&auml;hlen';

		// Club
		$this->validated['runregClub'] = trim(\Input::post('runregClub'));
		if(strlen($this->validated['runregClub']) < 5 || strlen($this->validated['runregClub']) > 50)
			$validationErrors[] = 'Bitte g&uuml;ltigen Vereins-, Team- oder Ortsnamen namen eingeben (5-50 Zeichen)';

		// GDPR
		$this->validated['runregGdpr'] = trim(\Input::post('runregGdpr'));
		if($this->validated['runregGdpr'] != 'read')
			$validationErrors[] = 'Bitte die Datenschutzerkl&auml;rung lesen.';

		return $validationErrors;
	}

  public function getRunObj()
	{
		$objRun = RunregistrationRunModel::getRunById($this->runId);
    if (!$objRun || !$objRun->next()) return false;
		return $objRun;
	}

  public function getRunTracksArray()
	{
		$objTracks = RunregistrationTrackModel::getTracksByRunId($this->runregistrationRun);

		$arrTracks = array();
		if($objTracks !== NULL)
		{
			while($objTracks->next())
			{
				if(!$objTracks->online_registration_enabled) continue;
				$arrTracks[$objTracks->id] = $objTracks->name;
			}
		}
		return $arrTracks;
	}

  private function captchaFmtPass($sum)
  {
    return 'runreg-'.$this->runId.':'.$sum;
  }

  protected function captchaGenerate()
  {
    $int1 = random_int(1, 9);
		$int2 = random_int(1, 9);
    $sum = $int1+$int2;
    $question = $GLOBALS['TL_LANG']['SEC']['question' . random_int(1, 3)];
    $_SESSION['runregCaptchaHash'] = password_hash($this->captchaFmtPass($sum), PASSWORD_DEFAULT);
    $this->Template->captchaQuestion = sprintf($question, $int1, $int2);
    $this->Template->captchaHash = $_SESSION['runregCaptchaHash'];
  }

  protected function captchaVerify()
  {
    if (!isset($_POST['runregSec']) || !isset($_POST['runregHash']) || !isset($_SESSION['runregCaptchaHash']) || $_POST['runregHash'] != $_SESSION['runregCaptchaHash'])
      return false;
    return password_verify($this->captchaFmtPass($_POST['runregSec']), $_SESSION['runregCaptchaHash']);
  }
}
?>
