<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rbac\models\FileScanner\factories;

use Rbac\models\FileScanner\strategy\ControllerStrategy;
use Rbac\models\FileScanner\FileScanner;
use Rbac\models\file\ControllerFile;

/**
 * Description of ControllerScannerFactory
 *
 * @author vlad
 */
class ControllerScannerFactory implements FactoryInterface {

	public function getScanner()
	{
		return new FileScanner(new ControllerStrategy(), new ControllerFile());
	}

}
