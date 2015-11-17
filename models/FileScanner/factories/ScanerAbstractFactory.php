<?php

namespace RbacRuleManager\models\FileScanner\factories;

/**
 * Description of ScanerAbstractFactory
 *
 * @author Sirenko Vlad
 */
class ScanerAbstractFactory {

	const CONTROLLER_FACTORY = 1;

	public static function getFactoryByCode($code)
	{
		switch ($code) {
			case self::CONTROLLER_FACTORY:
				return new ControllerScannerFactory();
		}
		
		throw new \Exception("Code {$code} not found in ScanerAbstractFactory");
	}

}
