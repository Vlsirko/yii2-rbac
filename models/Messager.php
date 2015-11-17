<?php

namespace RbacRuleManager\models;

/**
 * Description of Messager
 *
 * @author Sirenko Vlad
 */
class Messager {
	
	const SUCCSESS = 1;
	const FAILURE = 2;
	const WARNING = 3;
	const NOTE = 4;

	private static $instance = null;
	
	private function __construct()
	{
		
	}
	
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function confirm($question, $status = null)
	{
		echo $this->colorize($question . ': ', $status);
		$handle = fopen("php://stdin", "r");
		$line = fgets($handle);
		if (trim($line) != 'yes') {
			throw new \Exception('Aborted');
		}
	}
	
	public function showMessage($message, $status = null, $break = true){
		echo $this->colorize($message, $status);
		if($break){
			echo PHP_EOL;
		}
		return $this;
	}
	
	private function colorize($text, $status)
	{
		$out = "";
		switch ($status) {
			case self::SUCCSESS:
				$out = "32m"; 
				break;
			case self::FAILURE:
				$out = "31m"; 
				break;
			case self::WARNING:
				$out = "33m"; 
				break;
			case self::NOTE:
				$out = "34m";
				break;
			default:
				$out = "0m";
			
		}
			
		return "\033[" . $out . $text . "\033[0m";
	}

}
