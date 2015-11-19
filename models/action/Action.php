<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rbac\models\action;

use Rbac\models\file\ControllerFile;
use ReflectionMethod;
use Rbac\models\Permitions\Permission;
use Rbac\controllers\ObservableRbacController;

/**
 * Representing Controler Actions
 *
 * @author vlad
 */
class Action {

	public $module = null;
	private $reflectionMethod;
	private $controllerFile;
	private $name; 

	public function __construct(ReflectionMethod $method, ControllerFile $controller)
	{
		$this->reflectionMethod = $method;
		$this->controllerFile = $controller;
	}

	public function getClass()
	{
		return $this->reflectionMethod->class;
	}

	public function getDescription()
	{
		$descriptionArray = $this->controllerFile->executeControllerMethod(ObservableRbacController::ACTIONS_ALIAS_METHOD);
		return $descriptionArray[$this->getName()];
	}
	
	

	public function getName()
	{
		if(!$this->name){
			$this->name =strtolower(str_replace('action', '', $this->reflectionMethod->name));
		}
		return $this->name;
	}

	public function getRuleName()
	{
		return self::createIdentificator([
				$this->controllerFile->getIdentificator(),
				$this->getName()
		]);
	}

	public function getPermission()
	{
		return new Permission($this->getRuleName(), $this->getDescription());
	}

	public function isNew()
	{
		return !array_key_exists($this->getRuleName(), $this->controllerFile->getExistsPermissions());
	}
	
	public static function createIdentificator(array $params)
	{
		return implode('/', $params);
	}

}
