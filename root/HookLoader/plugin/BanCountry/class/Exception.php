<?php

class phpBB3_BanCountryException extends Exception
{
	function getException()
	{
		$message = '[BanCountry Error] ';
		$message .= $this->message;
		$message .= "\n";
		if (defined('DEBUG')) {
			$message .= '<br />';
			$message .= 'An error was thrown';
			$message .= ' in file ' . $this->file;
			$message .= ' on line ' . $this->line;
			$message .= '<br />';
			$message .= "\n";
			trigger_error($message, E_USER_ERROR);
		} else {
			trigger_error($message);
		}
	}
}