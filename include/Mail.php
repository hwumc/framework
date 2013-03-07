<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Mail
{
	public static function send($to, $subject, $content)
	{
		$headers = 
			"X-Mailer: HWUMC-Mailer/1.0 PHP/" . phpversion() . "\r\n" .
			"X-HWUMC-UserID: " . ( Session::isLoggedIn() ? Session::getLoggedInUser() : 0 ) . "\r\n" .
			"From: noreply@hwumc.co.uk";
		
		mail($to, $subject, $content, $headers);
	}
}