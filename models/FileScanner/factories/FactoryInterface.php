<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RbacRuleManager\models\FileScanner\factories;

/**
 *
 * @author vlad
 */
interface FactoryInterface {
	
	/**
	 * @return RbacRuleManager\models\FileScanner\FileScanner
	 */
	public function getScanner();
}
