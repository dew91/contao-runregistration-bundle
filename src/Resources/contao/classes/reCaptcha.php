<?php
/*
 * This is a PHP library that handles calling reCAPTCHA.
 */

if(!class_exists('reCaptcha'))
{
	class reCaptcha
	{
		private $pubkey;
		private $secret;
		
		public function __construct($pubkey, $secret)
		{
			$this->pubkey = $pubkey;
			$this->secret = $secret;
		}
		
		public function get_html()
		{
			return '<div class="g-recaptcha" data-sitekey="'.$this->pubkey.'"></div>';
		}

		public function check_answer($response, $remoteip)
		{
			$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->secret.'&response='.$response.'&remoteip='.$remoteip);
			
			$obj = json_decode($response);
			return $obj->success;
		}
	}
}
?>
