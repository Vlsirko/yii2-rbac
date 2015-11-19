<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rbac\models\FileScanner\factories;

/**
 *
 * @author vlad
 */
interface FactoryInterface {
	
	/**
	 * @return Rbac\models\FileScanner\FileScanner
	 */
	public function getScanner();
}
