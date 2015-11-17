<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RbacRuleManager\models\FileScanner\strategy;

/**
 * Description of FileScannerStrategyInterface
 *
 * @author sIRENKO vLAD
 */
interface FileScannerStrategyInterface {
	
	/**
	 * @return boolean 
	 */
	public function isFileSuitable($pathToFile);
	
}
