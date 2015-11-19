<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rbac\models\FileScanner\strategy;

/**
 * Description of ControllerStrategy
 *
 * @author Sirenko Vlad
 */
class ControllerStrategy implements FileScannerStrategyInterface {

	const OBSERVABLE_INTERFACE = 'ObservableRbacController';

	public function isFileSuitable($pathToFile)
	{


		return strpos($pathToFile, 'Controller.php') !== false && $this->isInterfaceImplements($pathToFile);
	}

	public function isInterfaceImplements($pathTofile)
	{
		$fileCode = file_get_contents($pathTofile);
		$pattern = "#implements [\w\\\]*" . self::OBSERVABLE_INTERFACE. "#im";
		return preg_match($pattern, $fileCode);
	}

}
