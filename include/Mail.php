<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Mail
{
	public static function send($to, $subject, $content)
	{        
        self::reallySend($to, $subject, $content, "");
	}	
    
    public static function sendHtml($to, $subject, $content)
	{
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        self::reallySend($to, $subject, $content, $headers);
	}
    
    private static function reallySend($to, $subject, $content, $headers)
	{
		$headers .= 
			"X-Mailer: HWUMC-Mailer/1.0 PHP/" . phpversion() . "\r\n" .
			"X-HWUMC-UserID: " . ( Session::isLoggedIn() ? Session::getLoggedInUser() : 0 ) . "\r\n" .
			"From: noreply@hwumc.co.uk";
		
		mail($to, $subject, $content, $headers);
	}
}