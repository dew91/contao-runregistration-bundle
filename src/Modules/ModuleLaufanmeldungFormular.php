<?php
namespace DominicErnst\LaufanmeldungBundle\Modules;

use DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungLaufModel;
use DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungStreckeModel;
use DominicErnst\LaufanmeldungBundle\Models\LaufanmeldungTeilnehmerModel;
use Contao;

// Google reCaptcha wrapper
require(__DIR__.'/../Classes/reCaptcha.php');

class ModuleLaufanmeldungFormular extends \Module
{
	/**
	 * @var string
	 */
	protected $strTemplate = 'mod_laufanmeldung_formular';
	protected $validated;
	protected $reCaptchaSecret;
	protected $reCaptchaPublic;
	protected $reCaptchaInstance;
	
	/**
	 * Do not display the module, if there are no menu items
	 *
	 * @return string
	 */
	public function generate()
	{
		if(TL_MODE == 'BE')
		{
			// Daten zum Lauf abfragen
			$objLauf = $this->getLaufdatenObj();
			
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');
			
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['ModuleLaufanmeldungFormular'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = '<b>Lauf:</b> '.$objLauf->name.'<br /><b>Modul-Name:</b> '.$this->name.'<br /><b>Modul-ID:</b> ';
			$objTemplate->href = 'contao?do=themes&table=tl_module&act=edit&id='.$this->id;
			
			return $objTemplate->parse();
		}
		
		return parent::generate();
	}
	
	/*
	 * Generate module
	 */
	public function compile()
	{
		// reCaptcha Secret aus Config laden:
		$this->reCaptchaSecret = $GLOBALS['DominicErnst']['LaufanmeldungBundle']['reCaptcha_Secret'];
		$this->reCaptchaPublic = $GLOBALS['DominicErnst']['LaufanmeldungBundle']['reCaptcha_SiteKey'];
		
		// reCaptcha-Instanz erzeugen
		$this->reCaptchaInstance = new \reCaptcha($this->reCaptchaPublic, $this->reCaptchaSecret);
		
		// === ALLGEMEINE FORMULARDATEN ÜBERGEBEN ===
		// URL zur Datenschutzerklärung
		$this->Template->url_dpr = $GLOBALS['DominicErnst']['LaufanmeldungBundle']['URL_DPR'];
		// Daten zum Lauf abfragen und an Template übergeben
		$this->Template->lauf_daten = $this->getLaufdatenObj();
		// Daten über verfügbare Strecken abfragen und übergeben
		$this->Template->lauf_strecken = $this->getLaufStreckenArr();
		// reCaptcha Code
		$this->Template->reCaptcha = $this->reCaptchaInstance->get_html();
		// Anmeldung abgeschlossen
		$this->Template->AnmeldungFertig = false;
		
		// Array für validierte Daten vorbereiten
		$this->validated = array(
			'laLaufName'		=> $this->Template->lauf_daten->name,
			'laGeschlecht'		=> 'm',
			'laVorname'			=> '',
			'laNachname'		=> '',
			'laGeburtstag'		=> '',
			'laAdresse'			=> '',
			'laPLZ'				=> '',
			'laOrt'				=> '',
			'laEMail'			=> '',
			'laStrecke'			=> '',
			'laVerein'			=> '',
			'laCaptcha'			=> '',
			'laDPR'				=> '',
			'laZeit'			=> time(),
			'laIP'				=> \Environment::get('remoteAddr'),
		);
		
		// Formular abgesendet?
		//if(isset($_POST['laGo']))
		if(\Input::post('laGo'))
		{
			// Daten validieren!
			$this->Template->validationErrors = $this->validateForm();
			if(!count($this->Template->validationErrors))
			{
				// Daten korrekt, Anmeldeformular ausblenden
				$this->Template->AnmeldungFertig = true;
				
				// Datensatz in Datenbank erstellen
				$objRunner = new LaufanmeldungTeilnehmerModel();
				$objRunner->pid = $this->validated['laStrecke'];
				$objRunner->tstamp = time();
				$objRunner->name = $this->validated['laNachname'];
				$objRunner->vorname = $this->validated['laVorname'];
				$objRunner->geschlecht = $this->validated['laGeschlecht'];
				$objRunner->geburtstag = strtotime(str_replace('.', '-', $this->validated['laGeburtstag']));
				$objRunner->email = $this->validated['laEMail'];
				$objRunner->strasse = $this->validated['laAdresse'];
				$objRunner->plz = $this->validated['laPLZ'];
				$objRunner->ort = $this->validated['laOrt'];
				$objRunner->verein = $this->validated['laVerein'];
				$objRunner->zeitanmeldung = time();
				$objRunner->ipanmeldung = $this->validated['laIP'];
				$objRunner->anmeldungaktiv = $GLOBALS['DominicErnst']['LaufanmeldungBundle']['AktivierungNotwendig']?'0':'1';
				$objRunner->save();
				
				// Anmeldebestätigung generieren
				$htmlmail = '<!DOCTYPE html><html><head><title>Anmeldebestägung</title></head><body style="font-family:Arial;">';
				$htmlmail .= 'Hallo '.$this->validated['laVorname'].',<br /><br />hiermit bestägen wir dir deine Anmeldung für folgende Laufveranstaltung:<br /><strong>'.$this->validated['laLaufName'].'</strong><br /><br />Hier nochmal eine Zusammenfassung deiner Daten:<br /><br />';
				$htmlmail .= '<strong><u>PERSÖNLICHE DATEN</u></strong><br />';
				$htmlmail .= 'Geschlecht: <i>'.($this->validated['laGeschlecht']=='m'?'männlich':'weiblich').'</i><br />';
				$htmlmail .= 'Vorname: <i>'.$this->validated['laVorname'].'</i><br />';
				$htmlmail .= 'Nachname: <i>'.$this->validated['laNachname'].'</i><br />';
				$htmlmail .= 'Geburtstag: <i>'.$this->validated['laGeburtstag'].'</i><br />';
				if($this->validated['laAdresse'] !='') $htmlmail .= 'Straße: <i>'.$this->validated['laAdresse'].'</i><br />';
				if($this->validated['laPLZ'] !='') $htmlmail .= 'PLZ: <i>'.$this->validated['laPLZ'].'</i><br />';
				if($this->validated['laOrt'] !='') $htmlmail .= 'Ort: <i>'.$this->validated['laOrt'].'</i><br />';
				$htmlmail .= 'E-Mail: <i>'.$this->validated['laEMail'].'</i><br /><br />';
				$htmlmail .= '<strong><u>ANGABEN ZUR VERANSTALTUNG</u></strong><br />';
				$htmlmail .= 'Datum: <i>'.date('d.m.Y', $this->Template->lauf_daten->date).'</i><br />';
				$htmlmail .= 'Strecke: <i>'.$this->Template->lauf_strecken[$this->validated['laStrecke']].'</i><br />';
				$htmlmail .= 'Verein/Team/Ort: <i>'.$this->validated['laVerein'].'</i><br /><br />';
				$htmlmail .= 'Die Startunterlagen können am Tag des Laufes an der Anmeldung abgeholt werden, bringen Sie dazu bitte einen Ausdruck dieser E-Mail mit.<br /><br />Viele Grüße<br />Dein Team der Mittelsächsischen Lauftour';
				$htmlmail .= '</body></html>';
				
				// Anmeldebestätigung per E-Mail senden
				$objEmail = new \Email();
				$objEmail->charset = 'UTF-8';
				$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
				$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
				$objEmail->subject = 'Ihre Anmeldung: '.$this->validated['laLaufName'];
				$objEmail->html = $htmlmail;
				$objEmail->sendTo($this->validated['laEMail']);
			}
		}
		
		// Validierte Daten an Template übergeben
		$this->Template->validated = $this->validated;
	}
	 
	 
	 /*
	  * getLaufdatenObj()
	  *
	  * List Daten zum Lauf aus der Datenbank und gibt Objekt zurück
	  */
	public function getLaufdatenObj()
	{
		$objLauf = LaufanmeldungLaufModel::getLaufdaten($this->laufanmeldung);
		$objLauf->next();
		return $objLauf;
	}
	
	/*
	 * getLaufStreckenArr()
	 *
	 * Gibt ein Array mit allen Strecken zum aktuellen Lauf zurück
	 */
	public function getLaufStreckenArr()
	{
		$objStrecken = LaufanmeldungStreckeModel::getLaufStrecken($this->laufanmeldung);
		
		$arrStrecken = array();
		if($objStrecken !== NULL)
		{
			while($objStrecken->next())
			{
				if(!$objStrecken->onlineanmeldung) continue;
				$arrStrecken[$objStrecken->id] = $objStrecken->name;
			}
		}
		return $arrStrecken;
	}
	
	/*
	 * validateForm()
	 *
	 * überprüft eingegebene Formulardaten auf Korrektheit
	 */
	public function validateForm()
	{
		$validationErrors = array();
		
		// Geschlecht überprüfen
		$this->validated['laGeschlecht'] = trim(\Input::post('laGeschlecht'));
		if(!in_array($this->validated['laGeschlecht'], array('m', 'w')))
			$validationErrors[] = 'Bitte Geschlecht ausw&auml;hlen';
		
		// Vorname
		$this->validated['laVorname'] = trim(\Input::post('laVorname'));
		if(strlen($this->validated['laVorname']) < 2 || strlen($this->validated['laVorname']) > 50)
			$validationErrors[] = 'Bitte g&uuml;ltigen Vorname eingeben (2-50 Zeichen)';
		
		// Nachname
		$this->validated['laNachname'] = trim(\Input::post('laNachname'));
		if(strlen($this->validated['laNachname']) < 2 || strlen($this->validated['laNachname']) > 50)
			$validationErrors[] = 'Bitte g&uuml;ltigen Nachname eingeben (2-50 Zeichen)';
		
		// Geburtstag
		$this->validated['laGeburtstag'] = trim(\Input::post('laGeburtstag'));
		if(!preg_match('/^([0-3][0-9])\.([0-1][0-9])\.([1-2][901][0-9][0-9])$/', $this->validated['laGeburtstag'], $_date) || !checkdate($_date[2], $_date[1], $_date[3]) || strtotime("$_date[1]-$_date[2]-$_date[3]") >= time())
			$validationErrors[] = 'Bitte g&uuml;tiges Geburtstdatum eingeben';
		
		// Straße und Hausnummer
		$this->validated['laAdresse'] = trim(\Input::post('laAdresse'));
		if($this->validated['laAdresse'] != '' && (strlen($this->validated['laAdresse']) < 5 || strlen($this->validated['laAdresse']) > 50 || count(explode(' ', $this->validated['laAdresse'])) < 2))
			$validationErrors[] = 'Bitte eine g&uuml;ltige Stra&szlig;e und Hausnummer eingeben (5-50 Zeichen)';
		
		// PLZ
		$this->validated['laPLZ'] = trim(\Input::post('laPLZ'));
		if($this->validated['laPLZ'] != '' && (!preg_match('/^[0-9]+$/', $this->validated['laPLZ']) || strlen($this->validated['laPLZ']) != 5))
			$validationErrors[] = 'Bitte eine g&uuml;ltige Postleitzahl eingeben (5 Zeichen)';
		
		// Ort
		$this->validated['laOrt'] = trim(\Input::post('laOrt'));
		if($this->validated['laOrt'] != '' && (strlen($this->validated['laOrt']) < 3 || strlen($this->validated['laOrt']) > 50))
			$validationErrors[] = 'Bitte einen g&uuml;ltigen Wohnort angeben (3-50 Zeichen)';
		
		// E-Mail
		$this->validated['laEMail'] = trim(\Input::post('laEMail'));
		if(!filter_var($this->validated['laEMail'], FILTER_VALIDATE_EMAIL))
			$validationErrors[] = 'Bitte eine g&uuml;ltige E-Mail-Adresse eingeben (10-50 Zeichen)';
		
		// Strecke
		$this->validated['laStrecke'] = trim(\Input::post('laStrecke'));
		if(!preg_match('/^[0-9]+$/', $this->validated['laStrecke']) || !isset($this->Template->lauf_strecken[$this->validated['laStrecke']]))
			$validationErrors[] = 'Bitte g&uuml;ltige Strecke ausw&auml;hlen';
		
		// Verein
		$this->validated['laVerein'] = trim(\Input::post('laVerein'));
		if(strlen($this->validated['laVerein']) < 5 || strlen($this->validated['laVerein']) > 50)
			$validationErrors[] = 'Bitte g&uuml;ltigen Vereins-, Team- oder Ortsnamen namen eingeben (5-50 Zeichen)';
		
		// Datenschutzrichtlinie
		$this->validated['laDPR'] = trim(\Input::post('laDPR'));
		if($this->validated['laDPR'] != 'read')
			$validationErrors[] = 'Bitte die Datenschutzerkl&auml;rung lesen.';
		
		// Captcha überprüfen
		$cap_response  = \Input::post('g-recaptcha-response');
		if(!$this->reCaptchaInstance->check_answer($cap_response, $this->validated['laIP']))
			$validationErrors[] = 'Sicherheitsfrage falsch beantwortet';
		
		// === FEHLERMELDUNGEN ZURÜCKGEBEN ===
		return $validationErrors;
	}
}
?>