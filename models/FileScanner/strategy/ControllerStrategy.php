<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RbacRuleManager\models\FileScanner\strategy;

/**
 * Description of ControllerStrategy
 *
 * @author Sirenko Vlad
 */
class ControllerStrategy implements FileScannerStrategyInterface{
	
	public function isFileSuitable($pathToFile)
	{
		return strpos($pathToFile, 'Controller.php') !== false;
	}

}
