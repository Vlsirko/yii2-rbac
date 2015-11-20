<?php

namespace Rbac\models\action;

use Rbac\models\file\ControllerFile;
use ReflectionMethod;
use Rbac\models\Permitions\Permission;
use Rbac\controllers\ObservableRbacController;

/**
 * Representing Controller Actions
 *
 * @author vlad
 */
class Action {

	public $module = null;
	private $reflectionMethod;
	private $controllerFile;
	private $name; 
	
	/**
	 * 
	 * @param ReflectionMethod $method 
	 * @param ControllerFile $controller 
	 */
	public function __construct(ReflectionMethod $method, ControllerFile $controller)
	{
		$this->reflectionMethod = $method;
		$this->controllerFile = $controller;
	}
	
	/**
	 * Returns class of controller of current action
	 * @return string
	 */
	public function getClass()
	{
		return $this->reflectionMethod->class;
	}
	
	/**
	 * Returns description of method. It will be used as label in dropdown list
	 * @return string
	 */
	public function getDescription()
	{
		$descriptionArray = $this->controllerFile->executeControllerMethod(ObservableRbacController::ACTIONS_ALIAS_METHOD);
		return $descriptionArray[$this->getName()];
	}
	
	/**
	 * Returns Action name
	 * @return string
	 */
	public function getName()
	{
		if(!$this->name){
			$this->name =strtolower(str_replace('action', '', $this->reflectionMethod->name));
		}
		return $this->name;
	}
	
	/**
	 * Generates identificator by which can find action permit in db
	 * @return string
	 */
	public function getIdentificator()
	{
		return self::createIdentificator([
				$this->controllerFile->getIdentificator(),
				$this->getName()
		]);
	}
	
	/**
	 * Returns Permission instance which represents this action
	 * @return Rbac\models\Permitions\Permission
	 */
	public function getPermission()
	{
		return new Permission($this->getIdentificator(), $this->getDescription());
	}
	
	/**
	 * Check existance of this action in db
	 * @return bool
	 */
	public function isNew()
	{
		return !array_key_exists($this->getIdentificator(), $this->controllerFile->getExistsPermissions());
	}
	
	/**
	 * @param array $params components of Action identificator
	 * @return string
	 */
	public static function createIdentificator(array $params)
	{
		return implode('/', $params);
	}

}
